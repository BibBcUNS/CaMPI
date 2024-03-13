<?php

//$verificar = file_get_contents("http://127.0.0.1/cgi-car/wxis.exe?IsisScript=catalis/verificarpw.xis&usuario=$usuario&pw=$pw");
$usuario=$_POST['usuario'];
$pw=$_POST['pw'];

$verificar = file_get_contents("https://campi-catalogacion.uns.edu.ar/catalis/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/verificarpw.xis&usuario=$usuario&pw=$pw");



if ($verificar != 'OK') {
?>  <!-- Esto es si ingresa mal la contraseña o usuario -->
	<HTML><HEAD><TITLE>Redireccionado</TITLE>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=/herramientas/index2.php">
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
	<!-- link rel="stylesheet" type="text/css" href="http://inmabb.criba.edu.ar/catalis/catalis.css"-->

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
		
		function validForm1(passForm) {
			
			// verificamos que se haya seleccionado una BD
			if (passForm.base.value == "") {
				alert("Debe indicar la Base de Datos sobre la que desea trabajar")
				passForm.base.focus()
				return false
			}

			// Controlamos que el campo ORIGENES no seté vacio
			if (passForm.mfns_origenes.value == "") {
				alert("Debe indicar el/los registro/s orígen")
				passForm.base.focus();
				return false
			}
			
			// ORIGENES No debe contener " "
			for (i = 0; i < passForm.mfns_origenes.value.length; i++)
			{
				caracter_i = passForm.mfns_origenes.value.charAt(i);  
				if ( (caracter_i != ",") && (!EsEntero(caracter_i)) )
				{
					alert("El campo ORÍGENES contiene caracteres inválidos.\nEvite espacios");
					passForm.mfns_origenes.focus();
					return false;
				}
			}

			// Controlamos que el campo DESTINO no esté vacio
			if (passForm.mfn_destino.value == "") {
				alert("Debe indicar el REGISTRO DESTINO")
				passForm.base.focus()
				return false
			}
			
			if (!EsEntero(passForm.mfn_destino.value)) {
				alert("El NÚMERO DE REGISTRO DESTINO no es un número válido")
				passForm.base.focus()
				return false
			}

			texto_de_aviso = "¿Está seguro que desea eliminar los registros \"" + passForm.mfns_origenes.value +
							 "\" en \"" + passForm.base.value + "\"?\n" +
						 	 "El campo v859 de dichos registros será copiados al registro \"" +
							 passForm.mfn_destino.value + "\""			
			acepta_eliminar = confirm(texto_de_aviso);
	
			if (acepta_eliminar) {
				passForm.submit.disabled = true;
				passForm.submit.value = 'Uniendo los registros...';
				return true;
			}
			else {
				return false;
			}			
		}


		function validForm2(passForm) {	
			// Verificamos que se haya seleccionado la base origen.
			if (passForm.fuente.selectedIndex < 1) {
				alert("Debe seleccionar la base ORIGEN")
				passForm.fuente.focus()
				return false
			}

			// Verificamos que se haya seleccionado la base origen.
			if (passForm.destino.selectedIndex < 1) {
				alert("Debe seleccionar la base DESTINO")
				passForm.destino.focus()
				return false
			}
			
			// Controlamos que el campo MFN DESTINO no seté vacio		
			if (passForm.nc_fuente.value == "") {
				alert("Debe indicar NÚMERO de registro")
				passForm.nc_fuente.focus()
				return false
			}
			
			// Controlamos que el NC sea un número.
			if (!EsEntero(passForm.nc_fuente.value)) {
				alert("El NÚMERO de registro no es válido")
				passForm.nc_fuente.focus()
				return false
			}

			texto_de_aviso = "¿Está seguro que desea eliminar el registro \"" + passForm.nc_fuente.value +
							 "\" en la base fuente?\n" +
						 	 "El Registro será movid a la base Destino"			
			acepta_eliminar = confirm(texto_de_aviso);
	
			if (acepta_eliminar) {
				passForm.submit.disabled = true;
				passForm.submit.value = 'Moviendo el registro...';
				return true;
			}
			else {
				return false;
			}			
		}

		
		</script>

		<style>
			body {
				margin:20px;
				text-align:center;
				background-color:#C9C7BA;
				font:Verdana, Arial, Helvetica, sans-serif;
				color:#FFFFFF;
			}
		</style>

	</head>

<body>
	

	<!-- UNION DE REGISTROS -->
	<form onsubmit="return validForm1(this)"
		action="unir_registros_do.php"
		method="post"
		style="background: brown;
		padding: 0px;
		margin: 10px;
		font-size: 16px;">
	<H3 style="padding:0; margin:0">Uni&oacute;n de registros</H3>
		<input type="hidden" name="usuario" value="<?php print $usuario?>">
		<input type="hidden" name="pw" value="<?php print $pw?>">
	<table border="0" cellspacing="10" align="center" width="100%">
	<tr><td align="right">Seleccione la BD</td><td align="left" width="55%">
		<select name=base>
		<?php
		echo '<option selected value=""></option>';		
		$basesxis = file_get_contents("https://campi-catalogacion.uns.edu.ar/catalis/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/bases.xis&usuario=$usuario");
		$bases = explode(":",$basesxis);
		for($i=0;$i<count($bases)-1;$i++){
		    // Esto es una restricción QUE HAY QUE BORRAR
			if ($bases[$i] == "eunm-p" || $bases[$i] == "ucod-marc-p" || $bases[$i] == "carpc" || $bases[$i] == "bibadm" || $bases[$i] == "bibeco" || $bases[$i] == "cems" || $bases[$i] == "demo")
			echo "<option value=$bases[$i]>$bases[$i]</option>";}
		?>
		</select>
	</td></tr>
	<tr><td align="right">Registros a ser movidos (borrados!)</td>
	<td align="left">
		<input type="text" name="mfns_origenes" size="30"></input><font face="Arial, Helvetica, sans-serif" size="-1">(separados por coma)</font>
	</td></tr>
	<tr><td align="right">Mover v859 a </td>
	<td align="left">
		<input type="text" name="mfn_destino" size="4"></input>
	</td></tr>
	<tr align="center"><td colspan="2"><input type="submit" name="submit" value="Unir registros"></td></tr>
	</table>
	</form>		
	
	<!--Mover registro-->
	<form onsubmit="return validForm2(this)"
		action="ucod_2_eunm_do.php"
		method="post"
		style="background: brown;
		border: 1px solid #F0F0F0;
		padding: 0px;
		margin: 20px;
		font-size: 16px;
		">
		<input type="hidden" name="usuario" value="<?php print $usuario?>">
		<input type="hidden" name="pw" value="<?php print $pw ?>">
		
	<table border="0" cellspacing="10" align="center">
    <tr><td><H3 style="padding:0; margin:0">Mover Registro</H3></td></tr>
	<tr><td>
    	Base 
    	<select name=fuente>
		<?php
		echo '<option selected value=""></option>';		
		for($i=0;$i<count($bases)-1;$i++){
		    // Esto es una restricción QUE HAY QUE BORRAR
			if ($bases[$i] == "ucod-marc-p"  || $bases[$i] == "bibadm" || $bases[$i] == "bibeco" || $bases[$i] == "eunm-p" || $bases[$i] == "huber" || $bases[$i] == "demo")
			echo "<option value=$bases[$i]>$bases[$i]</option>";}
		?>
		</select>
        N&uacute;mero de registro <input type="text" name="nc_fuente" size="6"></input>
	</td></tr>
	<tr><td>
        Moverlo a ====>>
   		<select name=destino>
		<?php
		echo '<option selected value=""></option>';		
		for($i=0;$i<count($bases)-1;$i++){
		    // Esto es una restricción QUE HAY QUE BORRAR
			if ($bases[$i] == "eunm-p" || $bases[$i] == "ead" || $bases[$i] == "eeo" || $bases[$i] == "eunm-ebook"  || $bases[$i] == "ucod-marc-p" || $bases[$i] == "demo")
			echo "<option value=$bases[$i]>$bases[$i]</option>";}
		?>
		</select>

	</td></tr>
	<tr align="center"><td>
		<b><input type="submit" name="submit" value="Mover registro"></b></td></tr>
	</table>
	</form>		
	
</body>
</html>
<?php } ?>
