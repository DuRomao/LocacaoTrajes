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
class Usuario {

    private function formulario() {
        return file_get_contents("view/html/usuario-form.html");
    }

    public function listar() {
        $grid = file_get_contents("view/html/usuarios.html");
        $linhas = "";
        $usuarioModel = new UsuarioModel();
        $registros = $usuarioModel->consultar();

        while ($registro = $registros->fetch(PDO::FETCH_ASSOC)) {
            if ($registro["tipo"] == 1) {
                $tipo = "Administrador";
            } elseif ($registro["tipo"] == 2) {
                $tipo = "Funcionário";
            }

            $ativo = ($registro["ativo"] == 1) ? "sim" : "não";

            $linhas .= "<tr>";
            $linhas .= "<td>" . $registro["nome"] . "</td>";
            $linhas .= "<td>" . $registro["email"] . "</td>";
            $linhas .= "<td>" . $tipo . "</td>";
            $linhas .= "<td>" . $ativo . "</td>";
            $linhas .= "<td><a href='index.php?modulo=Usuario&acao=editar&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-wrench'></i></button></a> <a href='index.php?modulo=Usuario&acao=excluir&chave={$registro['id']}'><button type='button' class='btn btn-default'><i class='glyphicon glyphicon-remove'></i></button></a></td>";
            $linhas .= "</tr>";
        }
        $html = str_replace("#LINHAS#", $linhas, $grid);

        return $html;
    }

    public function cadastrar() {
        $formulario = $this->formulario();
        $arrayTipos[1] = "Administrador";
        $arrayTipos[2] = "Funcionário";

        $optionTipos = Html::gerarOption($arrayTipos);

        $html = str_replace("#TITULO#", "Cadastrar Usuário", $formulario);
        $html = str_replace("#NOME#", "", $html);
        $html = str_replace("#EMAIL#", "", $html);
        $html = str_replace("#SENHA#", "", $html);
        $html = str_replace("#OPTIONTIPOS#", $optionTipos, $html);
        $html = str_replace("#CHECKED#", "checked", $html);
        $html = str_replace("#ID#", "", $html);

        return $html;
    }

    public function salvar() {
        if (isset($_POST["nome"])) {
            $ativo = isset($_POST["ativo"]);

            $usuarioModel = new UsuarioModel();
            $usuarioModel->setNome($_POST["nome"]);
            $usuarioModel->setEmail($_POST["email"]);
            $usuarioModel->setSenha($_POST["senha"]);
            $usuarioModel->setTipo($_POST["tipo"]);
            $usuarioModel->setAtivo($ativo);
            $usuarioModel->gravar(Aplicacao::$chave);

            header('Location: index.php?modulo=Usuario&acao=listar');

            return true;
        }
    }

    public function excluir() {
        if (Aplicacao::getValorSessao("tipoUsuario") == "ADMINISTRADOR") {
            if (Aplicacao::$chave != "") {
                $usuarioModel = new UsuarioModel();
                if ($usuarioModel->deletar(Aplicacao::$chave)) {
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
        $usuarioModel = new UsuarioModel();
        $usuarioModel->consultarPorId(Aplicacao::$chave);
        $arrayTipos[1] = "Administrador";
        $arrayTipos[2] = "Funcionário";

        $optionTipos = Html::gerarOption($arrayTipos, $usuarioModel->getTipo());

        $ativo = ($usuarioModel->getAtivo() == 1) ? "checked" : "";

        $html = str_replace("#TITULO#", "Editar Usuário", $formulario);
        $html = str_replace("#NOME#", $usuarioModel->getNome(), $html);
        $html = str_replace("#EMAIL#", $usuarioModel->getEmail(), $html);
        $html = str_replace("#SENHA#", $usuarioModel->getSenha(), $html);
        $html = str_replace("#OPTIONTIPOS#", $optionTipos, $html);
        $html = str_replace("#CHECKED#", $ativo, $html);
        $html = str_replace("#ID#", Aplicacao::$chave, $html);

        return $html;
    }

}
