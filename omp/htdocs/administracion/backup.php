<?php


// Preparo el directorio de backup y copio los archivos a respaldar
shell_exec(
    "rmdir /q/s backup &"
    ."mkdir backup &"
    ."xcopy /e/y ..\\..\\bases\\* backup\\"
);

// Comprimo los archivos
shell_exec(
    "cd backup &"
    ."c:\\CaMPI\\bin\\cisis\\7z\\7za a campi.zip *"
);

//Redirecciono el navegador para descargar el archivo
header("Location: backup/campi.zip");


?>