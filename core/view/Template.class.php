<?php

class Template {

    public static function iniciar() {
        $template = file_get_contents("view/html/template.html");

        if (class_exists(Aplicacao::$modulo)) {
            $classe_modulo = Aplicacao::$modulo;
            $objeto_modulo = new $classe_modulo;
            if (method_exists($objeto_modulo, Aplicacao::$acao)) {
                $acao_modulo = Aplicacao::$acao;
                $html = $objeto_modulo->$acao_modulo();
            } else {
                $html = "A ação: " . Aplicacao::$acao . " não existe no módulo " . Aplicacao::$modulo;
            }
        } else {
            $html = "O Módulo: " . Aplicacao::$modulo . " não existe";
        }

        $html2 = str_replace("#CENTRO#", $html, $template);
        $html2 = str_replace("#MENUADM#", GerenciarUsuarios::gerarMenuADM(Aplicacao::getValorSessao("tipoUsuario"), Aplicacao::getValorSessao("nomeUsuario")), $html2);

        return $html2;
    }

}
