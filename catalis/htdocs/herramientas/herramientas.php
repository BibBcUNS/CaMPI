<?php
$usuario=$_POST['usuario'];
$pw=$_POST['pw'];

$verificar = file_get_contents("http://localhost/catalis/cgi-bin/wxis?IsisScript=/biblio/xis/herramientas/verificarpw.xis&usuario=$usuario&pw=$pw");


if ($verificar != 'OK') {
?>  <!-- Esto es si ingresa mal la contraseña o usuario -->
	<HTML><HEAD><TITLE>Redireccionado</TITLE>
	<META HTTP-EQUIV="Refresh" CONTENT="0; URL=http://localhost/catalis/herramientas/index.php">
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
	<title>Herramientas de Catalis (Backup de bases de datos y impresión de etiquetas</title>
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
		    $basesxis = file_get_contents("http://localhost/catalis/cgi-bin/wxis?IsisScript=/biblio/xis/herramientas/bases.xis&usuario=$usuario");
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
	
	<!-- seleccion de la base para la impresió de etiquetas  -->
	<table class="catalis">
	<tr><td align="center">
		<h2>Impresion de Etiquetas</h2>
		<form onsubmit="return validForm(this)" action="etiquetas-procesar.php" method="post">
		<p>Seleccione la Base de datos de la cual quiere Imprimir etiquetas:<br> 
		<select name=base>
			<?php
				$basesxis = file_get_contents("http://localhost/catalis/cgi-bin/wxis?IsisScript=/biblio/xis/herramientas/bases.xis&usuario=$usuario");
				$bases = explode(":",$basesxis);
				for($i=0;$i<count($bases)-1;$i++){
					echo "<option value=$bases[$i]>$bases[$i]</option>";
				}
			?>
		</select>
		</p>	
		<p>
		Ingrese los núumeros de inventario:<br>
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
    

<?php } ?>


</center>
</body>
</html>
