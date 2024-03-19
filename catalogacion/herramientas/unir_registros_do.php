<?php
$usuario=$_POST['usuario'];
$pw=$_POST['pw'];
$base=$_POST['base'];
$mfns_origenes=$_POST['mfns_origenes'];
$mfn_destino=$_POST['mfn_destino'];

$verificar = file_get_contents("https://campi-catalogacion.uns.edu.ar/catalis/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/verificarpw.xis&usuario=$usuario&pw=$pw");
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

	<link rel="stylesheet" href="herramientas.css">

	</head>

<body>

	<nav id="navHerramientas">
		<h1>CaMPI Catalogaci&oacute;n - Herramientas</h1>        
	</nav>

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

	echo '<div style="text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: center;">';
		echo '<div style="width:600px;border:2px solid brown; background-color: #fed">';
		if (!$error) {
			echo '<h3>base de datos utilizada:</h3> <h2>',$base.'</h2><hr>';
			echo '<h3>Se eliminaron los siguientes registros:</h3>';
			echo '<ul style="font-size: 22px; list-style: none; padding: 0;">';
			for ( $i=0 ; $i<$cant_nros ; $i++){
				echo "<li>$numeros[$i]</li>";
			}
			echo '</ul> <hr>';

			echo '<h3>El campo v859 de dichos registros fue movido al registro<br>'.
			'<font color=red>Verifique las existencias en este registro</font></h3> <h2>'.$mfn_destino.'</h2>';
			shell_exec('cd /var/www/campi-catalogacion/catalis/htdocs/herramientas/union_registros;sh ./unir.sh '.$base.' "'.$nros_control.'" '.$mfn_destino.' '.$usuario);

		}else {
			echo "Ocurrió un error: El campo MFNs ORÍGENES o DESTINO, no debe contener espacios.<BR>";
			echo "Regrese al formulario anterior e intente modificar dicho campo";
		}
		echo '</div>';	

/*		echo '<form action="herramientas.php">';
		echo '<input type="hidden" name="usuario" value="'$usuario'">';
		echo '<input type="hidden" name="usuario" value="LV">';
		echo '<input type="hidden" name="pw" value="'$pw'"> <br>';
		echo '<input class="btnHerramientas" type="submit" value="Volver a Herramientas">';
		echo '</form>'
 */
	echo '</div>';

}?>
	
</body>
</html>
