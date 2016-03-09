<?php

header("Content-Type: text/html; charset=utf-8");

require("config/config.php");

function __autoload($classe) {
    $dirs = array("core/controller", "core/view", "controller", "model", "view");
    foreach ($dirs as $dir) {
        $arquivo = $dir . "/" . $classe . ".class.php";
        if (file_exists($arquivo)) {
            include_once ($arquivo);
        }
    }
}

$aplicacao = new Aplicacao();
$html = $aplicacao->iniciar();

echo $html;

