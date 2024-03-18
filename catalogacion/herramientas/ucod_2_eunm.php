<?
$usuario=$_POST['usuario'];
$pw=$_POST['pw'];

$verificar = file_get_contents("http://127.0.0.1/cgi-car/wxis.exe?IsisScript=catalis/verificarpw.xis&usuario=$usuario&pw=$pw");

if ($verificar != 'OK') {
?>  <!-- Esto es si ingresa mal la contraseña o usuario -->
	<HTML><HEAD><TITLE>Redireccionado</TITLE>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=/catalis">
	</HEAD><BODY></BODY></HTML>
<?
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

		<script type="text/javascript">
		
		function EsEntero(valor){
			//intento convertir a entero.
			//si era un entero no le afecta, si no lo era lo intenta convertir
			valor = parseInt(valor)
			
			//Compruebo si es un valor numérico
			if (isNaN(valor)) {
				//entonces (no es numero) devuelvo el valor cadena vacia
				return false
			}else{
				//En caso contrario (Si era un número) devuelvo el valor
				return true
			}
		}
		
		function validForm(passForm) {		
	
			// Controlamos que el campo MFN DESTINO no seté vacio
			if (passForm.mfn_destino.value == "") {
				alert("Debe indicar NÚMERO de registro")
				passForm.base.focus()
				return false
			}
			
			if (!EsEntero(passForm.nc_ucod.value)) {
				alert("El NÚMERO de registro no es válido")
				passForm.base.focus()
				return false
			}

			texto_de_aviso = "¿Está seguro que desea eliminar los registros \"" + passForm.mfns_origenes.value +
							 "\" en \"" + passForm.base.value + "\"?\n" +
						 	 "El campo v859 de dichos registros será copiados al registro con mfn \"" +
							 passForm.mfn_destino.value + "\""			
			acepta_eliminar = confirm(texto_de_aviso);
	
			if (acepta_eliminar) {
				return true;
			}
			else {
				return false;
			}			
		}

		
		</script>

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
	
	<form onsubmit="return validForm(this)"
		action="ucod_2_marc_do.php"
		method="post"
		style="background: brown;
		border: 1px solid #F0F0F0;
		padding: 6px;
		margin: 6px 0;
		font-size: 16px;
		">
		<input type="hidden" name="usuario" value="<?=$usuario?>">
		<input type="hidden" name="pw" value="<?=$pw?>">
	<table border="0" cellspacing="10" align="center">
	<tr><td colspan="2"><H3>Mover Registro de UCOD-MARC a EUNM</H3></td></tr>
	
	<tr><td align="right">
	Número de registro </td>
	<td align="left">
	<input type="text" name="nc_ucod" size="6"></input>
	</td></tr>
	<tr align="center"><td colspan="2">
	<br><input type="submit" value="Mover de UCOD-MARC a EUNM">
	</td></tr>
	</form>		
	</table>
	
</body>
</html>
<?php } ?>
