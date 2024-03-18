<?php

if (is_uploaded_file($HTTP_POST_FILES['archivo']['tmp_name'])) {
   mkdir("/var/www/html/catalis/herramientas/uploads/$base");
   copy($HTTP_POST_FILES['archivo']['tmp_name'], "/var/www/html/catalis/herramientas/uploads/$base/".$HTTP_POST_FILES['archivo']['name']);
   //shell_exec('cd /var/www/html/carpc/bases/hldgs;./actualizarHldgs.sh '.$institucion);
}
else {
   echo "Error al intentar transferir el archivos";
}
?> 