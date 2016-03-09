<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Locacao
 *
 * @author Hellen
 */
class Locacao {

    private function formulario() {
        return file_get_contents("view/html/locacao-form.html");
    }

    public function listar() {
        $grid = file_get_contents("view/html/locacoes-aberto.html");
        $linhas = "";

        $locacaoModel = new LocacaoModel();
        $produtoModel = new ProdutoModel();

        $registros = $locacaoModel->consultar(0);
        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {

            $produtos = explode(",", $registro["produtos"]);
            $listaProduto = "";
            foreach ($produtos as $produto) {
                $produtoModel->consultarPorId($produto);
                $listaProduto .= $produtoModel->getNome() . "<br>";
            }
            $linhas .= "<tr>";
            $linhas .= "<td>" . $listaProduto . "</td>";
            $linhas .= "<td>" . $registro["cliente"] . "</td>";
            $linhas .= "<td> R$" . $registro["valor_total"];
            $linhas .= "<td> <b>L:</b> " . date('d/m/Y', strtotime($registro["dia_locacao"])) . "<br> <b>D: </b>" . date('d/m/Y', strtotime($registro["dia_entrega"])) . "</td>";
            $linhas .= "<td><a href='index.php?modulo=Locacao&acao=info&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-zoom-in'></i></button></a><a href='index.php?modulo=Locacao&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=Locacao&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
            $linhas .= "</tr>";
        }

        $html = str_replace("#LINHAS#", $linhas, $grid);

        return $html;
    }

    public function listarConcluidas() {
        $grid = file_get_contents("view/html/locacoes-concluido.html");
        $linhas = "";

        $locacaoModel = new LocacaoModel();
        $clienteModel = new ClienteModel();
        $produtoModel = new ProdutoModel();

        $registros = $locacaoModel->consultar(1);
        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {

            $clienteModel->consultarPorId($registro["cliente_id"]);
            $produtos = explode(",", $registro["produtos"]);
            $listaProduto = "";
            foreach ($produtos as $produto) {
                $produtoModel->consultarPorId($produto);
                $listaProduto .= $produtoModel->getNome() . "<br>";
            }
            $linhas .= "<tr>";
            $linhas .= "<td>" . $listaProduto . "</td>";
            $linhas .= "<td>" . $clienteModel->getNome() . "</td>";
            $linhas .= "<td> R$" . $registro["valor_total"];
            $linhas .= "<td>" . date('d/m/Y', strtotime($registro["dia_entrega"])) . "</td>";
            $linhas .= "<td><a href='index.php?modulo=Locacao&acao=info&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-zoom-in'></i></button></a><a href='index.php?modulo=Locacao&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=Locacao&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
            $linhas .= "</tr>";
        }

        $html = str_replace("#LINHAS#", $linhas, $grid);

        return $html;
    }

    public function cadastrar() {
        $formulario = $this->formulario();
        $optionProdutos = $this->gerarProdutos();
        $optionClientes = $this->gerarClientes();
        $html = str_replace("#TITULO#", "Cadastro de Locacao", $formulario);
        $html = str_replace("#DESCRICAO#", "", $html);
        $html = str_replace("#OPTIONPRODUTOS#", $optionProdutos, $html);
        $html = str_replace("#OPTIONCLIENTES#", $optionClientes, $html);
        $html = str_replace("#DIALOCACAO#", "", $html);
        $html = str_replace("#DIAENTREGA#", "", $html);
        $html = str_replace("#USUARIO#", Aplicacao::getValorSessao("nomeUsuario"), $html);
        $html = str_replace("#ID#", "", $html);

        return $html;
    }

    private function gerarClientes($valor = null) {
        $clienteModel = new ClienteModel();
        $registros = $clienteModel->consultar();
        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $arrayClientes[$registro["id"]] = $registro ["nome"];
        }
        $optionCliente = Html::gerarOption($arrayClientes, $valor);
        return $optionCliente;
    }

    private function gerarProdutos($valor = null) {
        $produtoModel = new ProdutoModel();
        $registros = $produtoModel->consultar(1);
        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $arrayProdutos[$registro["id"]] = $registro["codigo"] . " - " . $registro ["nome"] . " - R$" . $registro["valor_aluguel"];
        }

        $optionProdutos = Html::gerarOption($arrayProdutos, $valor);

        return $optionProdutos;
    }

    private function gerarProdutosSelecionados($opcao) {
        $produtoModel = new ProdutoModel();
        $registros = $produtoModel->consultar();
        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $arrayProdutos[$registro["id"]] = $registro ["nome"] . " R$" . $registro["valor_aluguel"];
        }

        $html = "";
        $p = explode(",", $opcao);
        foreach ($arrayProdutos as $valor => $descricao) {
            if (in_array($valor, $p)) {
                $selected = "selected='selected'";
            } else {
                $selected = "";
            }
            $html .= "<option value='{$valor}'{$selected}>{$descricao}</option>";
        }
        $optionProdutos = $html;
        return $optionProdutos;
    }

    public function salvar() {
        if (isset($_POST["descricao"])) {
            $ativo = isset($_POST["ativo"]);
            $locacaoModel = new LocacaoModel();
            $usuarioModel = new UsuarioModel();
            $produtoModel = new ProdutoModel();
            $valor_total = 0.0;

            $loca = $_POST["dia_locacao"];
            $datalo = implode("/", array_reverse(explode("/", $loca)));
            $entr = $_POST["dia_entrega"];
            $dataen = implode("/", array_reverse(explode("/", $entr)));

            $usuarioModel->consultarIdPorNome(Aplicacao::getValorSessao("nomeUsuario"));
            $locacaoModel->setDescricao($_POST["descricao"]);
            $locacaoModel->setDia_entrega($dataen);
            $locacaoModel->setDia_locacao($datalo);
            $locacaoModel->setCliente_id($_POST["cliente_id"]);
            $locacaoModel->setAtivo($ativo);
            $locacaoModel->setUsuario_id($usuarioModel->getId());
            $produtos = implode($_POST["produtos"], ",");
            $locacaoModel->setProdutos($produtos);
            foreach ($_POST["produtos"] as $idProduto) {
                $produtoModel->consultarPorId($idProduto);
                $valor_total = $valor_total + $produtoModel->getValor_aluguel();
            }
            $this->statusProduto($_POST["produtos"], $ativo);
            $locacaoModel->setValor_total($valor_total);
            $locacaoModel->gravar(Aplicacao::$chave);
            if ($locacaoModel->getAtivo() == 0) {
                header('Location: index.php?modulo=Locacao&acao=listar');
            } else {
                header('Location: index.php?modulo=Locacao&acao=listarConcluidas');
            }
            return true;
        }
    }

    private function statusProduto($arrayProdutos, $statusLocacao) {
        $produtoModel = new ProdutoModel();
        foreach ($arrayProdutos as $idProduto) {
            $produtoModel->consultarPorId($idProduto);
            if ($statusLocacao == 1) {
                $produtoModel->setAtivo(1);
            } else {
                $produtoModel->setAtivo(0);
            }
            $produtoModel->gravar($idProduto);
        }
    }

    public function excluir() {
        if (Aplicacao::getValorSessao("tipoUsuario") == "ADMINISTRADOR") {
            if (Aplicacao::$chave != "") {
                $locacaoModel = new LocacaoModel();
                $locacaoModel->consultarPorId(Aplicacao::$chave);
                $produtos = explode(",", $locacaoModel->getProdutos());
                $this->statusProduto($produtos, 1);

                if ($locacaoModel->deletar(Aplicacao::$chave)) {
                    $html = '<p class="bg-success">Registro excluido com sucesso!</p>';
                } else {
                    $html = '<p class="bg-info">Não foi possível deletar a locação!</p>';
                }
                if ($locacaoModel->getAtivo() == 0) {
                    $html = $html . $this->listar();
                } else {
                    $html = $html . $this->listarConcluidas();
                }
            }
        } else {
            $html = '<p class="bg-danger">Você não tem permissão de excluir registros!</p>' . $this->listar();
        }

        return $html;
    }

    public function editar() {
        $formulario = $this->formulario();
        $html = str_replace("#TITULO#", "Editar Locacao", $formulario);
        $locacaoModel = new LocacaoModel();
        $locacaoModel->consultarPorId(Aplicacao::$chave);
        $usuarioModel = new UsuarioModel();
        $usuarioModel->consultarPorId($locacaoModel->getUsuario_id());
        $optionProdutos = $this->gerarProdutosSelecionados($locacaoModel->getProdutos());
        $optionClientes = $this->gerarClientes($locacaoModel->getCliente_id());
        $ativo = ($locacaoModel->getAtivo() == 0) ? "" : "checked";
        $html = str_replace("#DESCRICAO#", $locacaoModel->getDescricao(), $html);
        $html = str_replace("#DIALOCACAO#", date('d/m/Y', strtotime($locacaoModel->getDia_locacao())), $html);
        $html = str_replace("#DIAENTREGA#", date('d/m/Y', strtotime($locacaoModel->getDia_entrega())), $html);
        $html = str_replace("#OPTIONPRODUTOS#", $optionProdutos, $html);
        $html = str_replace("#OPTIONCLIENTES#", $optionClientes, $html);
        $html = str_replace("#CHECKED#", $ativo, $html);
        $html = str_replace("#USUARIO#", $usuarioModel->getNome(), $html);
        $html = str_replace("#ID#", Aplicacao::$chave, $html);


        return $html;
    }

    public function info() {
        $info = file_get_contents("view/html/locacao-info.html");

        $locacaoModel = new LocacaoModel();
        $locacaoModel->consultarPorId(Aplicacao::$chave);
        $clienteModel = new ClienteModel();
        $usuarioModel = new UsuarioModel();
        $produtoModel = new ProdutoModel();

        $clienteModel->consultarPorId($locacaoModel->getCliente_id());
        $usuarioModel->consultarPorId($locacaoModel->getUsuario_id());

        $produtos = explode(",", $locacaoModel->getProdutos());
        $listaProduto = "";
        foreach ($produtos as $produto) {
            $produtoModel->consultarPorId($produto);
            $listaProduto .= "<br>" . $produtoModel->getNome();
        }
        $ativo = ($locacaoModel->getAtivo() == 0) ? "em andamento" : "concluída <i class='glyphicon glyphicon-ok'></i>";

        $html = str_replace("#TITULO#", "Detalhes da Locacao", $info);
        $html = str_replace("#ATIVO#", $ativo, $html);
        $html = str_replace("#PRODUTOS#", $listaProduto, $html);
        $html = str_replace("#CLIENTE#", $clienteModel->getNome(), $html);
        $html = str_replace("#VALOR_TOTAL#", $locacaoModel->getValor_total(), $html);
        $html = str_replace("#DIALOCACAO#", date('d/m/Y', strtotime($locacaoModel->getDia_locacao())), $html);
        $html = str_replace("#DIAENTREGA#", date('d/m/Y', strtotime($locacaoModel->getDia_entrega())), $html);
        $html = str_replace("#USUARIO#", $usuarioModel->getNome(), $html);

        return $html;
    }

}
