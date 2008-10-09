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

<body bgcolor="#E8E8D0" topmargin="5" leftmargin="5" rightmargin="5"
>
<font face="Arial" size="3">

<form name="consultas" method="POST" action="/cgi-bin/wxis.exe/omp/circulacion/" onclick="javascript:focus_expresion();">
  <input type="hidden" name="IsisScript" value="omp/circulacion/consulta.xis">
	<table border="0" width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td width="100%"><strong>Consulta</strong></td></tr>
    <tr>
			<td width="100%"><input type="radio" name="criterio" value="inv" checked>Inventario</td></tr>
    <tr>
      <td width="100%"><input type="radio" name="criterio" value="mfn">Nro Reg.</td></tr>
    <tr>
      <td width="100%"><input type="radio" name="criterio" value="lector">Lector</td></tr>
    <tr>
      <td width="100%"><u>B</u>uscar: <br>
      <input type="text" name="expresion" size="15" accesskey="b"><br>
      <input type="submit" value="Enviar"><input type="reset" value="Limpiar"></td></tr>
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
			<td width="100%"><strong>Devolución</strong></td></tr>
    <tr>
      <td width="100%"><u>I</u>nventario: <br>
      <input type="text" name="inventario" size="5" accesskey="I">
			<input type="Hidden" name="operador">
			<input type="Hidden" name="clave">			
      <input type="submit" value="Enviar"></td></tr>
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
      <td width="100%"><strong>Identificación</strong></td></tr>
    <tr>
      <td width="100%">
      <input type="hidden" name="operador" value=<?php 
	    session_start();
	    $usuario=$_SESSION["s_username"];
		$url="http://127.0.0.1/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=omp/circulacion/obtener_pwd_opera.xis&id_operador=".$usuario;
		$ptr_grabar_datos = fopen($url,"r");
		$grabar_datos = fread($ptr_grabar_datos,500);
		fclose($ptr_grabar_datos);
	    echo $usuario.'-'.$grabar_datos;?>>
	  </td>
	 </tr>
    <tr>
      <td width="100%"><u>L</u>ector: <br>
      <input type="text" name="lector" size="15" accesskey="l"></td></tr>
    <tr>
      <td width="100%">
        <center><input type="submit" value="      Enviar      "></center><!-- input type="reset" value="Limpiar" onclick="window.document.form_identi.id_operador.value = '';window.document.form_identi.submit()"--><br>
        <input type="button" value="Limpiar Lector" onclick="window.document.form_id.lector.value='';window.document.form_id.lector.focus()">
        </td></tr>
  </table>
</form>
</font>

</body>
</html>
