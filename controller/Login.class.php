<?php

class Login {

    public function logar() {
        $mensagem = "";
        if (isset($_POST["email"])) {
            $email = $_POST["email"];
            $senha = $_POST["senha"];
            $usuarioModel = new UsuarioModel();
            if ($usuarioModel->validaLogin($email, $senha) == true) {
                Aplicacao::setValorSessao("logado", true);
                Aplicacao::setValorSessao("nomeUsuario", $usuarioModel->getNome());
                if ($usuarioModel->getTipo() == 1) {
                    Aplicacao::setValorSessao("tipoUsuario", "ADMINISTRADOR");
                } else if ($usuarioModel->getTipo() == 2) {
                    Aplicacao::setValorSessao("tipoUsuario", "FUNCIONARIO");
                }
                header("location:index.php?modulo=Pagina&acao=home");
            } else {
                $mensagem = "<p align='center'>Usuário ou senha inválidos!</p>";
            }
        }
        $login = file_get_contents("view/html/home.html");
        return $login . $mensagem;
    }

    public function logout() {
        Aplicacao::fecharSessao();
        header("location:index.php");
    }

}
