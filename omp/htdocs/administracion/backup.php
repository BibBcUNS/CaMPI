<?php

// Preparo el directorio de backup y copio los archivos a respaldar
shell_exec(
    "rm backup/*;"
    ."rm backup/;"
    ."mkdir backup/;"
    ."cp -r ../../bases/* backup/;"
);

//Comprimo los archivos
shell_exec(
    "cd backup/;"
    ."zip -rm campi.zip *;"
);

//Redirecciono el navegador para descargar el archivo
header("Location: backup/campi.zip");


?>