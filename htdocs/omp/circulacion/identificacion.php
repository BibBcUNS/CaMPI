<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<base target="principal">
<title>Open MarcoPolo - Módulo Circulación</title>
</head>

<body topmargin="12"  bgcolor="#E8E8D0">
<?php
session_start();
$usuario=$_SESSION["s_username"];
$url="http://127.0.0.1/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=omp/circulacion/identificacion_id.xis&id_operador=".$usuario;
$ptr_grabar_datos = fopen($url,"r");
$grabar_datos = fread($ptr_grabar_datos,500);
fclose($ptr_grabar_datos);
echo '<b><p align="center">OPERADOR<br>'.$grabar_datos.'</b></p>';
?>
</body>
</html>
