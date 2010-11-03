<?php  session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <base target="principal">
    <title>id_prestamo</title>
<link rel="stylesheet" type="text/css" href="/omp/css/style.css">	
<style type="text/css">
<!--
#lt1 {
   width : 130px;
   border : 1px solid #5277AE;
   padding : 0px 2px;
   font-family: "Trebuchet MS", Verdana, sans-serif;
   vertical-align:middle;
}


#lt1 h2 {
	margin:  0 -20px 0 0;
	color: #fff;
	text-align: center;
	font-size : 130%;
	background: #5277AE;
}

#lt1 td {   
        font-size: 14px;
		vertical-align:middle;
}


#lt1 form{   
       border:1px solid #5277AE;
	   border-width:1px 0px 0px 0px;
	   padding:5px 0px 5px 0px;
	   margin:5px;
}

#lt1 input[type=submit]{
	height:24px;
}

input {
	border:1px solid lightgray;
	height:20px;
	vertical-align:middle;
	margin-bottom:1px;
}

input:hover{
	border:1px solid Gray !important;
}

input:focus{
	border:1px solid Gray !important;
	background-color:Beige;
}

input[type="submit"] {
	border-left-width:0px;
}

-->
</style>

<SCRIPT language="JavaScript1.1">
<!--
	
function determinar() {
			text=window.document.consultas.expresion.value;
			numeros='';
			letras='';
			for (var i=0; i<text.length; i++) {
				var ch=text.substring(i,i+1);
				if (ch < "0" || ch > "9") {letras="si"} else {numeros = "si"}
				}

			if (numeros == "si") {
					if (text.length < 7) {
						window.document.consultas.criterio[0].click()}
					else {
						window.document.consultas.criterio[2].click()}
			}
			if (numeros != "si" && text.length >= 1) {
						window.document.consultas.criterio[2].click()};
}

/*Texto eliminado del input de texto de búsqueda:
onkeyup='setTimeout("determinar()",100)' 
*/

var ultimo = ""; 

//-->
function focus_expresion() {
	document.consultas.expresion.focus()
}
</SCRIPT>
</head>

<body>
   <div id="lt1" style="margin-top:50px;">
		<?php
        $usuario=$_SESSION["s_username"];
        $url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/identificacion_id.xis&id_operador=".$usuario;
        $ptr_datos = fopen($url,"r");
        $datos = fread($ptr_datos,500);
        fclose($ptr_datos);
        echo '<center><b>OPERADOR<br>'.$datos.'</b>';
		echo '<a href="/omp/logout.php" target="_top" style="text-decoration:none"><b>&gt;&gt;&gt; Salir &lt;&lt;&lt;</b></a></center>';
        ?>
	</div>
   <div id="lt1" style="margin-top:30px;">
   <!--h2>MENU</h2-->
<form name="consultas" method="POST" action="/omp/cgi-bin/wxis.exe/omp/circulacion/" onclick="javascript:focus_expresion();"
	style="border-top:0px;"
 onsubmit="
		if (window.document.consultas.expresion.value =='$' || window.document.consultas.expresion.value =='') {
				alert('Debe indicar una expresión válida')
				return false;
		}else{  return true;}
	">
	<input type="hidden" name="IsisScript" value="circulacion/consulta.xis">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%"><strong>Consultar por</strong></td></tr>
    <tr>
		<td width="100%">
		<input type="radio" name="criterio" value="inv" checked>Inventario
		</td>
	</tr>
	<?php
		include "json/JSON.php";
		$ptr_config = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/config_obtener.xis","r");
		$config_obtener = fread($ptr_config,1000);
		fclose($ptr_config);
		$json = new Services_JSON();
		$config = $json->decode($config_obtener);
		if ($config->busqueda_x_nc == 'si') {
		?>
			<tr>
				<td width="100%">
				<input type="radio" name="criterio" value="nc">Nº Control
				</td>
			</tr>
		<?php } // fi end ?>
    <tr>
		<td width="100%">
		<input type="radio" name="criterio" value="autor">Autor
		</td>
	</tr>
	<tr>
		<td width="100%">
		<input type="radio" name="criterio" value="lector">Usuario
		</td>
	</tr>
    <tr>
      <td width="100%">
	   <input type="text" name="expresion" size="10" value="" accesskey="b"><input type="submit" value=" > ">
	   <!--input type="submit" value="Buscar"-->
	  </td>
	</tr>
  </table>
</form>

<form name="form_devolucion" method="POST" action="/omp/cgi-bin/wxis.exe/omp/circulacion/"
	onsubmit="
		if (window.document.form_devolucion.inventario.value =='') {
				alert('Debe indicar algun inventario')
				return false;
		}else{
			window.document.form_devolucion.operador.value=window.document.form_id.operador.value;
			return true;}
	">
	<input type="hidden" name="IsisScript" value="circulacion/devolucion.xis">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="100%"><strong>Inventario (devol)</strong></td></tr>
    <tr>
      <td width="100%">
            <input type="text" name="inventario" value="" size="10" accesskey="I"><input type="submit" value=" > ">
            <!-- input type="submit" value="Devolver" -->
            <input type="Hidden" name="operador">
            <input type="Hidden" name="clave">			
      </td></tr>
  </table>
</form>

<?php 
$usuario=$_SESSION["s_username"];
$password=$_SESSION["s_password"];
?>

<form name="form_id" method="POST" action="/omp/cgi-bin/wxis.exe/omp/circulacion/"
 	onSubmit="
		if (window.document.form_id.lector.value =='') {
				window.document.form_id.lector.focus();
				return false;}
		else
				return true;
		">

  <input type="hidden" name="IsisScript" value="circulacion/prestamo.xis">
	<input type="hidden" name="opcion" value="ID">
	<table border="0" width="100%" cellpadding="0" cellpadding="">
    <tr>
      <td width="100%"><strong>ID del usuario</strong></td>
    </tr>
    <tr>
      <td width="100%">
      <input type="hidden" name="operador" value="<?php echo $usuario.'-'.$password; ?>">
	  <input type="text" id="lector" name="lector" size="10" accesskey="l"><input type="submit" value=" > ">
	  <!--input type="button" value=dni onclick="window.document.form_id.lector.value='DNI';window.document.form_id.lector.focus()"-->
	  <input type="button" value="limpiar" onclick="window.document.form_id.lector.value='';window.document.form_id.lector.focus()">
	  </td>
	</tr>
  </table>
</form>
</font>
</div>

</body>
</html>
