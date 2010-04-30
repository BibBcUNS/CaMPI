<?php
session_start();

//Comprobacion del envio del nombre de usuario y password
$username=$_POST['username'];
$password=$_POST['password'];
if ($password==NULL or $username==NULL) {
   echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login_form.php?error=si>";}
else{
   $cadena_archivo = "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=userpwd.xis&user=".$username."&pwd=".$password."&modulo=circulacion";
   $ptr_userpwd = fopen($cadena_archivo, "r");
   $exito = fread($ptr_userpwd,8192);
   fclose($ptr_userpwd);
   if($exito != 'OK') {
	  echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login_form.php?error=si>";}
   else{
      $_SESSION["s_username"] = $username;
	  $_SESSION["s_permiso"] = 'circulacion';
	  echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=circulacion.php>";
   }
}
?> 
