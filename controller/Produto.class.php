<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Usuario
 *
 * @author Hellen
 */
class Produto {

    private function formulario() {
        return file_get_contents("view/html/produto-form.html");
    }

    public function listar() {
        $grid = file_get_contents("view/html/produtos.html");
        $linhas = "";
        $produtoModel = new ProdutoModel();
        $registros = $produtoModel->consultar();

        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $imagem = $registro["imagem"];
            $ativo = ($registro["ativo"] == 1) ? "sim" : "não";
            $linhas .= "<tr>";
            $linhas .= "<td><img src='public/img/produtos/{$imagem}' height='50' width='50' class='img-rounded' /></td>";
            $linhas .= "<td>" . $registro["codigo"] . "</td>";
            $linhas .= "<td>" . $registro["nome"] . "</td>";
            $linhas .= "<td>" . $registro["categoria"] . "</td>";
            $linhas .= "<td> R$ " . $registro["valor_aluguel"] . "</td>";
            $linhas .= "<td>" . $ativo . "</td>";
            $linhas .= "<td><a href='index.php?modulo=Produto&acao=info&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-zoom-in'></i></button></a><a href='index.php?modulo=Produto&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=Produto&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
            $linhas .= "</tr>";
        }

        $html = str_replace("#LINHAS#", $linhas, $grid);

        return $html;
    }

    public function cadastrar() {
        $formulario = $this->formulario();
        $optionCategorias = $this->gerarSelectCategorias();

        $html = str_replace("#TITULO#", "Cadastrar Produto", $formulario);
        $html = str_replace("#CODIGO#", "", $html);
        $html = str_replace("#NOME#", "", $html);
        $html = str_replace("#COR#", "", $html);
        $html = str_replace("#TAMANHO#", "", $html);
        $html = str_replace("#VALOR_COMPRA#", "", $html);
        $html = str_replace("#VALOR_ALUGUEL#", "", $html);
        $html = str_replace("#OPTIONCATEGORIAS#", $optionCategorias, $html);
        $html = str_replace("#DESCRICAO#", "", $html);
        $html = str_replace("#CHECKED#", "checked", $html);
        $html = str_replace("#ID#", "", $html);

        return $html;
    }

    public function salvar() {
        if (isset($_POST["nome"])) {
            $arquivo = $_FILES["imagem"];
            $imagem = ($arquivo["name"] != "") ? $arquivo["name"] : $_POST["imagem"];
            move_uploaded_file($arquivo["tmp_name"], "public/img/produtos/" . $imagem);

            $ativo = isset($_POST["ativo"]);
            $produtoModel = new ProdutoModel();

            $produtoModel->setCodigo($_POST["codigo"]);
            $produtoModel->setNome($_POST["nome"]);
            $produtoModel->setCor($_POST["cor"]);
            $produtoModel->setTamanho($_POST["tamanho"]);
            $produtoModel->setValor_compra($_POST["valor_compra"]);
            $produtoModel->setValor_aluguel($_POST["valor_aluguel"]);
            $produtoModel->setDescricao($_POST["descricao"]);
            $produtoModel->setAtivo($ativo);
            $produtoModel->setCategoria_id($_POST["categoria"]);
            $produtoModel->setImagem($imagem);
            $produtoModel->gravar(Aplicacao::$chave);

            header('Location: index.php?modulo=Produto&acao=listar');

            return true;
        }
    }

    public function excluir() {
        if (Aplicacao::getValorSessao("tipoUsuario") == "ADMINISTRADOR") {
            if (Aplicacao::$chave != "") {
                $produtoModel = new ProdutoModel();
                if ($produtoModel->deletar(Aplicacao::$chave)) {
                    $html = '<p class="bg-success">Registro excluido com sucesso!</p>' . $this->listar();
                } else {
                    $html = '<p class="bg-info">Não foi possível deletar a locação!</p>' . $this->listar();
                }
            }
        } else {
            $html = '<p class="bg-danger">Você não tem permissão de excluir registros!</p>' . $this->listar();
        }
        return $html;
    }

    public function info() {
        $info = file_get_contents("view/html/produto-info.html");
        $html = str_replace("#TITULO#", "Informações do Produto", $info);
        $produtoModel = new ProdutoModel();
        $produtoModel->consultarPorId(Aplicacao::$chave);
        $ativo = ($produtoModel->getAtivo() == 1) ? "sim" : "não";
        $categoriaModel = new CategoriaModel();
        $categoriaModel->consultarPorId($produtoModel->getCategoria_id());
        $imagem = $produtoModel->getImagem();
        $html = str_replace("#CODIGO#", $produtoModel->getCodigo(), $html);
        $html = str_replace("#NOME#", $produtoModel->getNome(), $html);
        $html = str_replace("#CATEGORIA#", $categoriaModel->getNome(), $html);
        $html = str_replace("#COR#", $produtoModel->getCor(), $html);
        $html = str_replace("#TAMANHO#", $produtoModel->getTamanho(), $html);
        $html = str_replace("#VALOR_COMPRA#", $produtoModel->getValor_compra(), $html);
        $html = str_replace("#VALOR_ALUGUEL#", $produtoModel->getValor_aluguel(), $html);
        $html = str_replace("#ATIVO#", $ativo, $html);
        $html = str_replace("#DESCRICAO#", $produtoModel->getDescricao(), $html);
        $html = str_replace("#IMAGEM#", "<img src='public/img/produtos/{$imagem}' width='200' class='img-rounded' />", $html);

        return $html;
    }

    public function editar() {
        $formulario = $this->formulario();
        $html = str_replace("#TITULO#", "Editar Produto", $formulario);
        $produtoModel = new ProdutoModel();
        $produtoModel->consultarPorId(Aplicacao::$chave);
        $optionCategorias = $this->gerarSelectCategorias($produtoModel->getCategoria_id());

        $ativo = $produtoModel->getAtivo() ? "checked" : "";
        $imagem = $produtoModel->getImagem();
        $html = str_replace("#CODIGO#", $produtoModel->getCodigo(), $html);
        $html = str_replace("#NOME#", $produtoModel->getNome(), $html);
        $html = str_replace("#COR#", $produtoModel->getCor(), $html);
        $html = str_replace("#TAMANHO#", $produtoModel->getTamanho(), $html);
        $html = str_replace("#VALOR_COMPRA#", $produtoModel->getValor_compra(), $html);
        $html = str_replace("#VALOR_ALUGUEL#", $produtoModel->getValor_aluguel(), $html);
        $html = str_replace("#OPTIONCATEGORIAS#", $optionCategorias, $html);
        $html = str_replace("#DESCRICAO#", $produtoModel->getDescricao(), $html);
        $html = str_replace("#CHECKED#", $ativo, $html);
        $html = str_replace("#IMAGEM", $imagem, $html);
        $html = str_replace("#ID#", Aplicacao::$chave, $html);

        return $html;
    }

    private function gerarSelectCategorias($valor = null) {
        $categoriaModel = new CategoriaModel();
        $registros = $categoriaModel->consultar();
        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $arrayCategorias[$registro["id"]] = $registro ["nome"];
        }
        return Html::gerarOption($arrayCategorias, $valor);
    }
    public function pesquisa() {
        if (isset($_POST["pesquisar"])) {

            $termo = $_POST["pesquisar"];
            $linhas = "";
            $produtoModel = new ProdutoModel();
            $registros = $produtoModel->pesquisarTermo($termo);
            while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $imagem = $registro["imagem"];
            $ativo = ($registro["ativo"] == 1) ? "sim" : "não";
            $linhas .= "<tr>";
            $linhas .= "<td><img src='public/img/produtos/{$imagem}' height='50' width='50' class='img-rounded' /></td>";
            $linhas .= "<td>" . $registro["codigo"] . "</td>";
            $linhas .= "<td>" . $registro["nome"] . "</td>";
            $linhas .= "<td>" . $registro["categoria"] . "</td>";
            $linhas .= "<td> R$ " . $registro["valor_aluguel"] . "</td>";
            $linhas .= "<td>" . $ativo . "</td>";
            $linhas .= "<td><a href='index.php?modulo=Produto&acao=info&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-zoom-in'></i></button></a><a href='index.php?modulo=Produto&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=Produto&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
            $linhas .= "</tr>";
        }
        $grid = file_get_contents("view/html/produtos.html");
        $html = str_replace("#LINHAS#", $linhas, $grid);

        return $html;
        }
    }

}
