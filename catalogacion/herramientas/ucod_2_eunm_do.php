<?php
	// Importo las variables de configuracion para que funcione en el entorno configurado
	$configs = include("config/config.php");

	$usuario=$_POST['usuario'];
	$pw=$_POST['pw'];
	$nc_fuente=$_POST['nc_fuente'];
	$fuente=$_POST['fuente'];
	$destino=$_POST['destino'];

	
	$verificar = file_get_contents( $configs['host'] . "/catalis/cgi-bin/wxis?IsisScript=../../herramientas/xis/verificarpw.xis&usuario=$usuario&pw=$pw");
	
	
	if ($verificar != 'OK') {
?>  
		<!-- Esto es si ingresa mal la contraseña o usuario -->	
		<html>
		<head>
			<title>Redireccionado</title>
			<!-- <META HTTP-EQUIV="Refresh" CONTENT="0; URL=/"> -->
		</head>
		<body>
   		</body>
		</html>

<?php
	}
	else 
	{
?>
		<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
		"http://www.w3.org/TR/html4/loose.dtd">
		<html>
		<head>
			<title>Documento sin t&iacute;tulo</title>
			<!--link rel="stylesheet" type="text/css" href="http://inmabb.criba.edu.ar/catalis/catalis.css"-->
			<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
			<link rel="stylesheet" href="herramientas.css">
		</head>
		<body>
			<nav id="navHerramientas">
				<h1>CaMPI Catalogaci&oacute;n - Herramientas</h1>        
			</nav>
<?php
			$nc_fuente = str_pad($nc_fuente,6,"0", STR_PAD_LEFT);
			shell_exec('cd ' . $configs['root_path'] . 'catalis/htdocs/herramientas/union_registros;sh ./ucod_2_eunm.sh '.$nc_fuente.' '.$usuario.' '.$fuente.' '.$destino);
			echo '<div style="background: brown; border: 1px solid #F0F0F0;	padding: 18px; margin: 6px 0; font-size: 16px;">'.
				'El registro con N&uacute;mero de Control (NC) <b style="font-size:1.5em">'.$nc_fuente.'</b> en la base <font style="font-size:1.1em">'.$fuente.' </font> fue movido al final de la base <font style="font-size:1.1em">'.$destino.' </font>'.
				 /*'<br><form action=herramientas.php>'.
				 	'<input type="hidden" name="usuario" value="'.$usuario.'">'.
				 	'<input type="hidden" name="pw" value="'.$pw.'">'.
					'<input class="btnHerramientas" type="submit" value="Volver a Herramientas">'.
					'</form>'.*/
				 '</div>';

}
?>
		</body>
		</html>
