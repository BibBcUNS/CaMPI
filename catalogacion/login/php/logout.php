<?php

    // Cierra la sesion general del navegador 
    session_start();
    session_destroy();

    // Tomar modulo por get

    // Cierra la sesion del Modulo y lo lleva al Logeo correspondiente

    $modulo = $_GET["modulo"];

    $url = "login.php?modulo=" . $modulo;

    header('Location: ' . $url);
?>