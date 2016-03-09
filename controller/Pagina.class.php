<?php

class Pagina {

    public function home() {
        if (Aplicacao::getValorSessao("logado") == true) {
            $html = file_get_contents("view/html/home-admin.html");
        } else {
            $html = file_get_contents("view/html/home.html");
        }
        return $html;
    }
}
