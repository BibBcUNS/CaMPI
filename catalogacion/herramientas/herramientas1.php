<?php
$usuario=$_POST['usuario'];
$pw=$_POST['pw'];

$verificar = file_get_contents("http://catalis.uns.edu.ar/cgi-bin/catalis_pack_en_produccion/wxis?IsisScript=catalis/xis/herramientas/verificarpw.xis&usuario=$usuario&pw=$pw");


if ($verificar != 'OK') {
?>  <!-- Esto es si ingresa mal la contrase�a o usuario -->
	<HTML><HEAD><TITLE>Redireccionado</TITLE>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=/herramientas">
	</HEAD>
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
	<title>Herramientas de Catalis (Backup de bases de datos y impresi�n de etiquetas</title>
	<link rel="stylesheet" type="text/css" href="herramientas.css">

		<script type="text/javascript">
	
		function validForm(passForm) {
			if (passForm.base.value == "") {
				alert("Debe indicar la base")
				passForm.base.focus()
				return false
			}
			return true
		}		
		</script>

		<style>
			body {
				margin-top:20px;
				text-align:center;
				background-color:#C9C7BA;
			}
		</style>
	</head>
	<body>
	<center>
<!-- --------------------------------------------------------------------------------------------------
Seccion descarga de Base de Datos
-------------------------------------------------------------------------------------------------- -->
	<!-- seleccion de la base  -->
	<table class="catalis"><tr><td align="center">
	    <form onsubmit="return validForm(this)" action="downloads/procesar.php" method="post">
	    <h2>Descarga de Base de Datos</h2>
	    <p>Base:    
		<select name=base>
	        <?php
		    $basesxis = file_get_contents("http://catalis.uns.edu.ar/cgi-bin/catalis_pack_en_produccion/wxis?IsisScript=catalis/xis/herramientas/bases.xis&usuario=$usuario");
		    $bases = explode(":",$basesxis);
		    for($i=0;$i<count($bases)-1;$i++){
			echo "<option value=$bases[$i]>$bases[$i]</option>";
		    }
			?>
        </select>
	    </p>
	<!-- seleccion del Sistema Operativo -->	
	    <p>
		Sistema destino:
		<select name="so" size="2">
		<option value="windows" selected="selected">windows</option>
		<option value="linux">linux</option>
		</select>
	    </p>	
	    <p><input type="submit" value="         b a j a r         "></p>
	    </form>
	</td></tr></table>
<!-- -----------------------------------------------------------------------------------------------------
Impresion Etiquetas
------------------------------------------------------------------------------------------------------ -->	
	
	<!-- seleccion de la base para la impresi� de etiquetas  -->
	<table class="catalis">
	<tr><td align="center">
		<h2>Impresion de Etiquetas</h2>
		<form onsubmit="return validForm(this)" action="etiquetas-procesar.php" method="post">
		<p>Seleccione la Base de datos de la cual quiere Imprimir etiquetas:<br> 
		<select name=base>
			<?php
				$basesxis = file_get_contents("http://catalis.uns.edu.ar/cgi-bin/catalis_pack_en_produccion/wxis?IsisScript=catalis/xis/herramientas/bases.xis&usuario=$usuario");
				$bases = explode(":",$basesxis);
				for($i=0;$i<count($bases)-1;$i++){
					echo "<option value=$bases[$i]>$bases[$i]</option>";
				}
			?>
		</select>
		</p>	
		<p>
		Ingrese los n�umeros de inventario:<br>
		(Uno debajo del otro, al final no dejar espacios)<br>
		<textarea name="inventarios" rows="10">
		</textarea>
		</p>
	</td></tr>	
	<tr><td align="center">
	    <h3> Tama&#241o de la Etiqueta </h3>
	    <input type="radio" name="tamanio" value="chica"> 2,5 cm (alto) x 5 cm (ancho) <br>
	    <input type="radio" name="tamanio" value="grande" checked="checked"> 4,4 cm (alto) x 5,5 cm (ancho) <br>	
	    <p><input type="submit" value="   Generar  Etiquetas    "></p>
	</td></tr>	
	</form>
	
	</table>
<!-- ------------------------------------------------------------------------------------------------------
Control de Stock
--------------------------------------------------------------------------------------------------------- -->
    <form onsubmit="return validForm(this)" action="control_stock.php" method="post" style="width: 75%;
		background: brown;
		border: 1px solid #F0F0F0;
		padding: 6px;
		margin: 6px 0;
		color: #F0F0F0;
		font-size: 16px;
		font:Verdana, geneva, sans-serif">
	<h2>Control de Stock y Generacion de Etiquetas</h2><br>
	Seleccione la Base de datos de la cual quiere generar el listado <br> 

		<select name=base>
		<?php
		$basesxis = file_get_contents("http://catalis.uns.edu.ar/cgi-bin/catalis_pack_en_produccion/wxis?IsisScript=catalis/xis/herramientas/bases.xis&usuario=$usuario");
		$bases = explode(":",$basesxis);
		for($i=0;$i<count($bases)-1;$i++){
			echo "<option value=$bases[$i]>$bases[$i]</option>";}
		?>
	</select><br><br>

	<table>
	<tr><td align="center">
	Ingrese los n&uacute;meros de inventario:
	<br>(Uno debajo del otro, al final no dejar espacios)
	<br>
		<textarea name="inventarios" rows="10">
		</textarea><br><br>
	</td><td align=center>
	    <select name=sector>
	    Sector:
		<option value=ALL>Todos</option> 
		<option value=SA>BC - Sector A</option>
	    	<option value=SB>BC - Sector B</option>
		<option value=SC>BC - Sector C</option>
		<option value=PA>BC - Sector A - ANDRES</option>
		<option value=c-ms>BC - Sector A - CONS</option>
		<option value=SCS>BC - Sector A - CS</option>
		<option value=cscs>BC - Sector A - cons. CS</option>
		<option value=SAT>BC - Sector A - Tesis</option>
		<option value=referencias>BC - Referencias</option>
		<option value=LF>BC - Sector A - Lopez Frances</option>
		<option value=ST>BC - Tesis</option>
		<option value=MM>BC - Multimedios</option>
		<option value=CDB>BC - CDB</option>
		<option value=hemeroteca>BC - Hemeroteca</option>
		<option value=T>BC - Tesoro</option>
		<option value=UIPT>BC - Uso interno PT</option>
		<option value=UIS>BC - Uso interno S</option>
		<option value=UID>BC - Uso interno D</option>
		<option value=SL>BC - Sala de lectura</option>
		<option value=SAA>BC - Sector A - Dpto. Administracion</option>
	    </select><br>
	    	    
	</td></tr>
	<tr><td colspan="2" align=center>
	    <p>Signatura Inicio: <input type="text" name="sig_ini" size="4">     
	    Libristica Inicio: <input type="text" name="sig_ini_lib" size="4"><br><br>
	    Signatura Final: <input type="text" name="sig_fin" size="4">
	    Libristica Final: <input type="text" name="sig_fin_lib" size="4"></p>
	</td></tr>
	</table>
	<h2>Seleccione el tipo de listado que desea generar</h2>
	<input type="radio" name="tipo_listado" value="imprimir_etiquetas">Impresion de Etiquetas<br>
	<input type="radio" name="tipo_listado" value="revision_estanterias">Revision de Estanterias<br>
	<input type="radio" name="tipo_listado" value="control_stock">Control de Stock<br>
	<input type="submit" value="   Comprobar    ">
	</form>

<?php } ?>


</center>
</body>
</html>