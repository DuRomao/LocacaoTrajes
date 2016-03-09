<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Html
 *
 * @author Hellen
 */
class Html {

    public static function gerarOption($array, $opcao = null) {
        $html = "";
        foreach ($array as $valor => $descricao) {
            $selected = ($valor == $opcao) ? "selected='selected'" : "";
            $html .= "<option value='{$valor}'{$selected}>{$descricao}</option>";
        }
        return $html;
    }

}
