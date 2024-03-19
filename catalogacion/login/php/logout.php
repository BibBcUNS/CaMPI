<?php

    // Cierra la sesion general del navegador 
    session_start();
    session_destroy();

    // Tomar modulo por get

    // Cierra la sesion del Modulo y lo lleva al Logeo correspondiente
    $url = '../../catalis/cgi-bin/wxis?IsisScript=catalis/xis/catalis.xis&userid=AUTO&db=auto&tarea=FIN_SESION';

    header('Location: ' . $url);
?>
<script>window.close()</script>