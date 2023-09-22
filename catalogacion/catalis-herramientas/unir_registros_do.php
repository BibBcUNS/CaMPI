<?php
$usuario=$_POST['usuario'];
$pw=$_POST['pw'];

$verificar = file_get_contents("http://catalis.uns.edu.ar/cgi-bin/catalis_pack_en_produccion/wxis?IsisScript=catalis/xis/herramientas/verificarpw.xis&usuario=$usuario&pw=$pw");

if ($verificar != 'OK') {
?>  <!-- Esto es si ingresa mal la contraseña o usuario -->
	<HTML><HEAD><TITLE>Redireccionado</TITLE>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=/catalis">
	</HEAD><BODY></BODY></HTML>
<?php
}
else {
?>
	<!-- Aca comienzan las herramientas, en caso de log correcto -->
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Merge registros</title>
	<!--link rel="stylesheet" type="text/css" href="http://inmabb.criba.edu.ar/catalis/catalis.css"-->

		<style>
			body {
				margin-top:20px;
				text-align:center;
				background-color:#C9C7BA;
				font:Verdana, Arial, Helvetica, sans-serif;
			}
		</style>

	</head>

<body>

	<?php 
	$error = false;
	$numeros = explode(",", $mfns_origenes);
	$cant_nros = count($numeros);
	$nros_control = "";
	for ( $i=0 ; $i<$cant_nros ; $i++){
		$numeros[$i] = str_pad($numeros[$i],6,"0", STR_PAD_LEFT);
		$nros_control .= $numeros[$i] . ($i<($cant_nros-1)?' ':'');
		$error = strpos(' ',$numeros[$i]) || $error;
	}
	
	$mfn_destino = str_pad($mfn_destino,6,"0", STR_PAD_LEFT);
	$error = strpos(' ',$mfn_destino) || $error;
	
	echo '<center><div style="width:600px;border:2px solid brown;">';
	
	if (!$error) {
		echo '<h5>base de datos utilizada:</h5> <h2>',$base.'</h2><hr>';
		echo '<h5>Se eliminaron los siguientes registros:</h5><h2><ul>';
			for ( $i=0 ; $i<$cant_nros ; $i++){
				echo "<li>$numeros[$i]</li>";
			}
		echo '</ul></h2><hr>';
		echo '<h5>El campo v859 de dichos registros fue movido al registro<br>'.
			'<font color=red>Verifique las existencias en este registro</font></h5> <h2>'.$mfn_destino.'</h2>';
		shell_exec('cd /var/www/catalis/htdocs/herramientas/union_registros;sh ./unir.sh '.$base.' "'.$nros_control.'" '.$mfn_destino.' '.$usuario);

		
	}
	else {
		echo "Ocurrió un error: El campo MFNs ORÍGENES o DESTINO, no debe contener espacios.<BR>";
		echo "Regrese al formulario anterior e intente modificar dicho campo";
	}
		echo '</div></center>';	

}?>
	
</body>
</html>
