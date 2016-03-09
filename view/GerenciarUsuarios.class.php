<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu
 *
 * @author Hellen
 */
class GerenciarUsuarios {

    public static function gerarMenuADM($tipoUsuario, $nomeUsuario=NULL) {
        if ($tipoUsuario == "ADMINISTRADOR") {
            $menu = file_get_contents("view/html/menu-admin.html");
        } elseif ($tipoUsuario == "FUNCIONARIO") {
            $menu = file_get_contents("view/html/menu-func.html");
        } else {
            $menu = file_get_contents("view/html/menu-visit.html");
        }
        $html = str_replace("#USUARIOL#", $nomeUsuario, $menu);
        return $html;
    }

}
