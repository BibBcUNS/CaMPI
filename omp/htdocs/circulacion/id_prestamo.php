<?php  session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <base target="principal">
    <title>id_prestamo</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />	
<style type="text/css">
<!--
#lt1 {
   float : left;
   width : 130px;
   margin : 0 0em 2em 0em;
   border : 1px solid #5277AE;
   padding : 0;
   background : #BEE4FF;
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
	   border-bottom-width:0px;
	   padding:10px 0px 10px 0px;
}

#lt1 input[type=submit]{
	height:24px;
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

/*Texto eliminado del input de texto de b�squeda:
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
 <body>
   <br><br>
   <div id="lt1">
   <h2>MENU</h2>
<form name="consultas" method="POST" action="/omp/cgi-bin/wxis.exe/omp/circulacion/" onclick="javascript:focus_expresion();"
 onsubmit="
		if (window.document.consultas.expresion.value =='$' || window.document.consultas.expresion.value =='') {
				alert('Debe indicar una expresi�n v�lida')
				return false;
		}else{  return true;}
	">
  <input type="hidden" name="IsisScript" value="circulacion/consulta_campi.xis">
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
		<input type="radio" name="criterio" value="nc">N� Control
		</td>
	</tr>
    <tr>
		<td width="100%">
		<input type="radio" name="criterio" value="lector">Usuario
		</td>
	</tr>
    <tr>
      <td width="100%">
	   <input type="text" name="expresion" size="10" value="" accesskey="b">
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
			<td width="100%"><strong>Devolver x inventario</strong></td></tr>
    <tr>
      <td width="100%">
            <input type="text" name="inventario" value="" size="10" accesskey="I">
            <!-- input type="submit" value="Devolver" -->
            <input type="Hidden" name="operador">
            <input type="Hidden" name="clave">			
      </td></tr>
  </table>
</form>

<?php 
$usuario=$_SESSION["s_username"];
$url="http://$_SERVER[SERVER_NAME]/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/obtener_pwd_opera.xis&id_operador=".$usuario;
$ptr_grabar_datos = fopen($url,"r");
$grabar_datos = fread($ptr_grabar_datos,500);
//fclose($ptr_grabar_datos);
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
      <td width="100%"><strong>Ingrese ID. Usuario</strong></td>
    </tr>
    <tr>
      <td width="100%">
      <input type="hidden" name="operador" value="<?php echo $usuario.'-'.$grabar_datos; ?>">
	  <input type="text" name="lector" size="8" accesskey="l"><input type="submit" value="Ver">
	  </td>
	</tr>
  </table>
</form>
</font>
</div>

<div id="lt1"><div id="lt1">
		<?php
        $usuario=$_SESSION["s_username"];
        $url="http://$_SERVER[SERVER_NAME]/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/identificacion_id.xis&id_operador=".$usuario;
        $ptr_datos = fopen($url,"r");
        $datos = fread($ptr_datos,500);
        fclose($ptr_datos);
        echo '<center><b>OPERADOR<br></center>'.$datos.'</b>';
        ?>
</div></div>

</body>
</html>