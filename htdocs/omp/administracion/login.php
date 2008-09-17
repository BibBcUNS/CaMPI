<?php
session_start();

//Comprobacion del envio del nombre de usuario y password
$username=$_POST['username'];
$password=$_POST['password'];
if ($password==NULL) {
echo "La password no fue enviada";
}else{
$cadena_archivo = "http://127.0.0.1/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/administracion/userpwd.xis&user=".$username;
$ptr_userpwd = fopen($cadena_archivo, "r");
$userpwd = fread($ptr_userpwd,8192);
fclose($ptr_userpwd);
if($userpwd != $password) {
echo "Login incorrecto";
}else{
$_SESSION["s_username"] = $username;
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=menu.php>";
}
}
?> 
