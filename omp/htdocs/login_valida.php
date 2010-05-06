<?php
session_start();

//Comprobacion del envio del nombre de usuario y password
$username=$_POST['username'];
$password=$_POST['password'];
$modulo=$_POST['modulo'];

if ($password==NULL or $username==NULL) {
   echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login_form.php?error=si&modulo=$modulo>";}
else{
   $cadena_archivo = "http://localhost:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=permisos_user.xis&user=".$username."&pwd=".$password;
   $ptr_userpwd = fopen($cadena_archivo, "r");
   $permisos = fread($ptr_userpwd,8192);
   fclose($ptr_userpwd);
   include "circulacion/json/JSON.php";
   $json = new Services_JSON();
   $permisos = $json->decode($permisos);
   if(!in_array($modulo,$permisos)) {
	  echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login_form.php?error=si&modulo=$modulo>";}
   else{
      $_SESSION["s_username"] = $username;
      $_SESSION["s_password"] = $password;
	  $_SESSION["s_permisos"] = $permisos;
      switch($modulo) {
		case 'circulacion':
			echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=circulacion/circulacion.php>";
			break;
		case 'estadisticas':
			echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=estadisticas/estadisticas.php>";
			break;
		case 'administracion':
			echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=administracion/menu.php>";
			break;
		default: break;
	}
   }
}
?> 
