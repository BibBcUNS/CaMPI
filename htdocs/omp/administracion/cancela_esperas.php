<html>
<head>
<title>Open MarcoPolo - Cancela Esperas Vencidas</title>

</head>
<body bgcolor="#E8E8D0" topmargin="0">

<?php

// Muestro el t�tulo
echo '<h2 style="text-align=center">Cancela Esperas Vencidas</h2>';
$url="http://127.0.0.1/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/administracion/cancela_esperas.xis";
 $ptr_esperas = fopen($url,"r");
 $esperas = fread($ptr_esperas,8192);
 echo $esperas;
 fclose($ptr_esperas);
 
?>

</body>
</html>