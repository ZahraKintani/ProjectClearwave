<?php

$controller = $_GET['c'] ?? 'DonasiController';
$method = $_GET['m'] ?? 'loginForm';

$controllerFile = "controller/$controller.php";

if (file_exists($controllerFile)) {
    require_once "controller/Controller.php";
    require_once $controllerFile;

    if (class_exists($controller)) {
        $c = new $controller;

        if (method_exists($c, $method)) {
            $c->$method();
        } else {
            echo "Method '$method' tidak ditemukan dalam controller '$controller'.";
        }
    } else {
        echo "Class '$controller' tidak ditemukan.";
    }
} else {
    echo "File controller '$controller.php' tidak ditemukan.";
    echo "<br>Path yang dicari: $controllerFile";
    echo "<br><a href='index.php?c=DonasiController&m=pilihandonasi'>Klik di sini untuk ke halaman donasi</a>";
}

?>