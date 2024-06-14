<?php 
header('Content-Type: text/html; charset=ISO-8859-1'); 
session_start(); ?>

<?php
if (isset($_SESSION["s_username"])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Módulo de Administración </title>
    <link rel="stylesheet" type="text/css" href="/omp/css/style.css">
</head>
 <body>
    <div id="head"> 
		<div id="title">Módulo de Administración - OPEN MarcoPolo</div>
		<div id="logo"><img src="/omp/images/logocampi.gif" width="120" height="54"></div>
    </div> 
    <div id="body_wrapper">
      <div id="body">
					 <div id="all">
								<div class="top"></div>
								<div class="content">
<!------------------------------------------------------------------------------------------------->

<?php

// Muestro el título
echo '<h2 style="text-align:center">Cancela Esperas Vencidas</h2>';
$url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/cancela_esperas.xis";
 $ptr_esperas = fopen($url,"r");
 $esperas = fread($ptr_esperas,8192);
 echo $esperas;
 fclose($ptr_esperas);
 
?>
<!------------------------------------------------------------------------------------------------->  
								</div>
								<div class="bottom"></div>
						</div>
        <div class="clearer">
</div>
      </div>
      <div class="clearer">
	  </div>
    </div>
    <div id="end_body"></div>

			<div id="footer"></div>
</body>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.html>";
}
?>
</html>
