<?php  session_start(); ?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<base target="principal">
<title>Open MarcoPolo - Módulo Circulación</title>
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

<body bgcolor="#E8E8D0" topmargin="10" leftmargin="2" rightmargin="2" style="font-face:Arial;font-size:11pt;">

<form name="consultas" method="POST" action="/cgi-bin/wxis.exe/omp/circulacion/" onclick="javascript:focus_expresion();"
 onsubmit="
		if (window.document.consultas.expresion.value =='$' || window.document.consultas.expresion.value =='') {
				alert('Debe indicar una expresión válida')
				return false;
		}else{  return true;}
	">
  <input type="hidden" name="IsisScript" value="omp/circulacion/consulta_campi.xis">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%"><strong>Consultar por</strong></td></tr>
    <tr>
		<td width="100%">
		<input type="radio" name="criterio" value="inv" checked>Inventario
		</td>
	</tr>
    <tr>
		<td width="100%">
		<input type="radio" name="criterio" value="nc">Nº Control
		</td>
	</tr>
    <tr>
		<td width="100%">
		<input type="radio" name="criterio" value="lector">Usuario
		</td>
	</tr>
    <tr>
      <td width="100%">
	   <input type="text" name="expresion" size="13" value="" accesskey="b">
	   <input type="submit" value="Buscar">
	  </td>
	</tr>
  </table>
</form>

<form name="form_devolucion" method="POST" action="/cgi-bin/wxis.exe/omp/circulacion/"
	onsubmit="
		if (window.document.form_devolucion.inventario.value =='') {
				alert('Debe indicar algun inventario')
				return false;
		}else{
			window.document.form_devolucion.operador.value=window.document.form_id.operador.value;
			return true;}
	">
  <input type="hidden" name="IsisScript" value="omp/circulacion/devolucion.xis">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
		<tr>
			<td width="100%"><strong>Devolver x inventario</strong></td></tr>
    <tr>
      <td width="100%"><input type="text" name="inventario" value="" size="7" accesskey="I">
	        <input type="submit" value="Devolver">
			<input type="Hidden" name="operador">
			<input type="Hidden" name="clave">			
      </td></tr>
  </table>
</form>


<form name="form_id" method="POST" action="/cgi-bin/wxis.exe/omp/circulacion/"
 	onSubmit="
		if (window.document.form_id.lector.value =='') {
				window.document.form_id.lector.focus();
				return false;}
		else
				return true;
		">

  <input type="hidden" name="IsisScript" value="omp/circulacion/prestamo.xis">
	<input type="hidden" name="opcion" value="ID">
	<table border="0" width="100%">
    <tr>
      <td width="100%"><strong>Estado de cuenta x ID.</strong></td></tr>
    <tr>
      <td width="100%">
      <input type="hidden" name="operador" value=<?php 
	    $usuario=$_SESSION["s_username"];
		$url="http://$SERVER_NAME/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=omp/circulacion/obtener_pwd_opera.xis&id_operador=".$usuario;
		$ptr_grabar_datos = fopen($url,"r");
		$grabar_datos = fread($ptr_grabar_datos,500);
		fclose($ptr_grabar_datos);
	    echo $usuario.'-'.$grabar_datos;?>>
	  </td>
	 </tr>
    <tr>
      <td width="100%">
	  <input type="text" name="lector" size="10" accesskey="l">
	  <input type="submit" value="Buscar">
	  </td>
	</tr>
    <tr>
      <td width="100%">
        <input type="button" value="Limpiar Lector" onclick="window.document.form_id.lector.value='';window.document.form_id.lector.focus()">
        </td></tr>
  </table>
</form>
</font>

</body>
</html>
