<?php
$usuario=$_POST['usuario'];
$pw=$_POST['pw'];
$verificar = file_get_contents("http://catalis.uns.edu.ar/cgi-bin/catalis_pack_en_produccion/wxis?IsisScript=catalis/xis/herramientas/verificarpw.xis&usuario=$usuario&pw=$pw");
print $verificar;
if ($verificar != 'OK') {
?>  <!-- Esto es si ingresa mal la contraseña o usuario -->
	<HTML><HEAD><TITLE>Redireccionado</TITLE>
	<!-- <META HTTP-EQUIV="Refresh" CONTENT="0; URL=/"> -->
	</HEAD><BODY>
   
    hola Fallo
    </BODY></HTML>
<?php
}
else {
	?>
	
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<title>Documento sin t&iacute;tulo</title>
	<!--link rel="stylesheet" type="text/css" href="http://inmabb.criba.edu.ar/catalis/catalis.css"-->
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

	<style>
			body {
				margin-top:20px;
				text-align:center;
				background-color:#C9C7BA;
				font:Verdana, Arial, Helvetica, sans-serif;
				color:#FFFFFF;
			}
	</style>

	</head>
	<body>
	<?php
		$nc_fuente = str_pad($nc_fuente,6,"0", STR_PAD_LEFT);
		shell_exec('cd /var/www/catalis/htdocs/herramientas/union_registros;sh ./ucod_2_eunm.sh '.$nc_fuente.' '.$usuario.' '.$fuente.' '.$destino);
		echo '<div style="background: brown; border: 1px solid #F0F0F0;	padding: 18px; margin: 6px 0; font-size: 16px;">'.
		     'El registro con Número de Control (NC) <b style="font-size:1.5em">'.$nc_fuente.'</b> en la base <font style="font-size:1.1em">'.$fuente.' </font> fue movido al final de la base <font style="font-size:1.1em">'.$destino.' </font>'.
			 '<br><form action=unir_registros.php>'.
			 	'<input type="hidden" name="usuario" value="'.$usuario.'">'.
			 	'<input type="hidden" name="pw" value="'.$pw.'">'.
				'<input type="submit" value="Volver a Herramienta">'.
			 '</form>'.
			 '</div>';

}?>


</body>
</html>
