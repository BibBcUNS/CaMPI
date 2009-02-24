<?php session_start(); ?>
<html>
<head>
<title>Open MarcoPolo - Cancela Esperas Vencidas</title>

</head>
<?php
if (isset($_SESSION["s_username"])) {
?>
<body bgcolor="#E8E8D0" topmargin="0">

<?php

// Muestro el título
echo '<h2 style="text-align=center">Cancela Esperas Vencidas</h2>';
$url="http://$SERVER_NAME/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/administracion/cancela_esperas.xis";
 $ptr_esperas = fopen($url,"r");
 $esperas = fread($ptr_esperas,8192);
 echo $esperas;
 fclose($ptr_esperas);
 
?>

</body>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.html>";
}
?>
</html>