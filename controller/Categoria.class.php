<?php

class Categoria {

    private function formulario() {
        return file_get_contents("view/html/categoria-form.html");
    }

    public function listar() {
        $grid = file_get_contents("view/html/categorias.html");
        $linhas = "";

        $categoriaModel = new CategoriaModel();

        $registros = $categoriaModel->consultar();
        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            $linhas .= "<tr>";
            $linhas .= "<td>" . $registro["nome"] . "</td>";
            $linhas .= "<td><a href='index.php?modulo=Categoria&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=Categoria&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
            $linhas .= "</tr>";
        }
        $html = str_replace("#LINHAS#", $linhas, $grid);
        return $html;
    }

    public function cadastrar() {
        $formulario = $this->formulario();
        $html = str_replace("#TITULO#", "Cadastro de Categoria", $formulario);
        $html = str_replace("#NOME#", "", $html);
        $html = str_replace("#ID#", "", $html);
        return $html;
    }

    public function salvar() {
        if (isset($_POST["nome"])) {
            $categoriaModel = new CategoriaModel();
            $categoriaModel->setNome($_POST["nome"]);
            $categoriaModel->gravar(Aplicacao::$chave);
            header('Location: index.php?modulo=Categoria&acao=listar');
            return true;
        }
    }

    public function excluir() {
        if (Aplicacao::getValorSessao("tipoUsuario") == "ADMINISTRADOR") {
            if (Aplicacao::$chave != "") {
                $categoriaModel = new CategoriaModel();
                if ($categoriaModel->deletar(Aplicacao::$chave)) {
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

    public function editar() {
        $formulario = $this->formulario();
        $html = str_replace("#TITULO#", "Editar Categoria", $formulario);
        $categoriaModel = new CategoriaModel();
        $categoriaModel->consultarPorId(Aplicacao::$chave);
        $html = str_replace("#NOME#", $categoriaModel->getNome(), $html);
        $html = str_replace("#ID#", "&chave=" . Aplicacao::$chave, $html);

        return $html;
    }

    public function pesquisa() {
        if (isset($_POST["pesquisar"])) {

            $termo = $_POST["pesquisar"];
            $linhas = "";
            $categoriaModel = new CategoriaModel();
            $registros = $categoriaModel->pesquisarTermo($termo);
            while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
                $linhas .= "<tr>";
                $linhas .= "<td>" . $registro["nome"] . "</td>";
                $linhas .= "<td><a href='index.php?modulo=Categoria&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=Categoria&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
                $linhas .= "</tr>";
            }
            $grid = file_get_contents("view/html/categorias.html");
            $html = str_replace("#LINHAS#", $linhas, $grid);
            return $html;
        }
    }

}
