<?php

class Aplicacao {

    public static $modulo;
    public static $acao;
    public static $chave;

    public function iniciar() {
        self::iniciarSessao();
        self::$modulo = isset($_GET["modulo"]) ? $_GET["modulo"] : MODULO_PADRAO;
        self::$acao = isset($_GET["acao"]) ? $_GET["acao"] : ACAO_PADRAO;
        self::$chave = isset($_GET["chave"]) ? $_GET["chave"] : "";

        if (Aplicacao::$modulo != "Pagina") {
            if (Aplicacao::getValorSessao("logado") != true) {
                Aplicacao::$modulo = "Login";
                Aplicacao::$acao = "logar";
            }
        }
        $html = Template::iniciar();

        return $html;
    }

    public static function iniciarSessao() {
        session_start();
    }

    public static function setValorSessao($nome, $valor) {
        $_SESSION[$nome] = $valor;
    }

    public static function getValorSessao($nome) {
        if (isset($_SESSION[$nome])) {
            return $_SESSION[$nome];
        }
    }

    public static function fecharSessao() {
        session_destroy();
    }

}
