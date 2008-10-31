<?php
session_start();

//Comprobacion del envio del nombre de usuario y password
$username=$_POST['username'];
$password=$_POST['password'];
if ($password==NULL or $username==NULL) {
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login_form.php?error=si>";
}else{
$cadena_archivo = "http://$SERVER_NAME/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/userpwd.xis&user=".$username;
$ptr_userpwd = fopen($cadena_archivo, "r");
$userpwd = fread($ptr_userpwd,8192);
fclose($ptr_userpwd);
if($userpwd != $password) {
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login_form.php?error=si>";
}else{
$_SESSION["s_username"] = $username;
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=menu.php>";
}
}
?> 
