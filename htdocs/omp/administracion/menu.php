<html>
<head>
<title>Menú del sistema MarcoPolo</title>
<!--script language=javascript type=text/javascript src=js/popup_calendar.js-->
</head>
<?php
session_start();
if (isset($_SESSION["s_username"])) {
?>
<body bgcolor="#E8E8D0" topmargin="0">
<table border="0" width="100%">
  <tr>
    <td width="85" rowspan="2"><img src="images/logo.gif" width="80" height="80" align="middle"></td>
    <td width="50%"><big>Biblioteca Central - UNS</big><td width="50%" align="right"><b><i>Sistema de Circulación <a href="/index.htm"><img src="/omp/omp.gif" width="80" height="62" align="middle" border="0"></a></i></b><br>
    </td>
  </tr>
</table>
<hr>
      
<table border="0" width="80%">
	<tr>
    	<td colspan="2" bgcolor="#FDD08E"><strong>Altas y Modificaciones del Calendario</strong></td>
  	</tr>
	<tr>
    <td colspan="2">
	
	  <form method="POST" action="calendario.php" target="calendario" onsubmit="window.open('', 'calendario', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,height=420,width=750,scrollbars=no,status=no')">
	  <strong>Año: <input type="text" name="anio" size="4"></strong>
      	  
      <input type="radio" name="opcion" value="EDICION" checked>Editar 
	  <input type="radio" name="opcion" value="ANIONUEVO">Crear año
  
	  <input type="submit" value="Ejecutar">
      </form>
	</td>
  </tr>
  <tr>
    	<td colspan="2" bgcolor="#FDD08E"><strong>Cancela Esperas Vencidas</strong></td>
  	</tr>
	<tr>
    <td colspan="2">
	
	  <form method="POST" action="cancela_esperas.php" target="esperas" onsubmit="window.open('', 'esperas', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,height=420,width=750,scrollbars=yes,status=no')">
	  <input type="submit" value="Cancela Esperas Vencidas">
      </form>
	</td>
  </tr>
    	<tr>
    	<td colspan="2" bgcolor="#FDD08E"><strong>Altas, Bajas y Modificaciones de Políticas de Circulación</strong></td>
  	</tr>
	<tr>
    <td colspan="2">
	
	  <form method="POST" action="politicas.php" target="politicas" onsubmit="window.open('', 'politicas', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,height=420,width=750,scrollbars=no,status=no')">
	  <input type="submit" value="ABM de Políticas">
      </form>
	</td>
  </tr>
  
  <tr>
    <td colspan="2" bgcolor="#FDD08E"><strong>Altas y Modificaciones de Lectores</strong></td>
  </tr>
  <tr>
    <td width="70%"><form method="POST" action="/cgi-bin/wxis.exe/omp/administracion/">
      <input type="hidden" name="IsisScript" value="omp/administracion/abmlector.xis">
      <input type="hidden" name="opcion" value="Presentar">
      <strong>Lector: <input type="text" name="credencial" size="10">
      <input type="radio" name="id_lector" value="documento" checked>
      DNI <input type="radio" name="id_lector" value="mfn">
      MFN&nbsp;&nbsp; <input type="submit" value="Presentar">
    </form></td>
    <td width="30%">
    <form method="POST" action="/cgi-bin/wxis.exe/omp/administracion/">
      <input type="hidden" name="IsisScript" value="omp/administracion/abmlector.xis">
      <input type="hidden" name="opcion" value="Registro Nuevo">
      <input type="hidden" name="invocado" value="menu.html">
      <input type="submit" value="Crear Nuevo Registro">
    </form></strong>
    </td>
    </form>
  </tr>
  <tr>
    <td bgcolor="#FDD08E"><strong>Circulación Bibliográfica</strong></td>
    <td bgcolor="#FDD08E"><strong>Otros procedimientos</strong></td>
  </tr>
  <tr>
    <td>
    	<form name="form_listados" method="POST" action="/cgi-bin/wxis.exe/omp/administracion/">
    	 <input type="hidden" name="IsisScript" value="omp/administracion/listados.xis">
	 <input type="Hidden" name="Orden" value="Prestamo">	
        <input type="radio" value="morosos" name="opcion"><strong>Listado de morosos<br>
        <input type="radio" value="prestamos" name="opcion">Prestamos del día (Estadística)<br>
        <input type="radio" value="id_recibos" name="opcion" checked>Devoluciones del día (Identificación de recibos)<br>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				Ordenamiento:
				<select onchange="window.document.form_listados.Orden.value=this.value;" style="font-size : xx-small">
				<option value="Prestamo" selected>Nro. Papeleta</option>
				<option value="Devolucion">Cronológico</option>							
				</select><br>
        <input type="radio" value="circulante" name="opcion">Prestamos en circulaci&oacute;n<br>				
        <input type="submit" value="Enviar" name="B1"></strong>
        <input type="reset" value="Restablecer" name="B2"> 
       </form>
    </td>
    <td width="51%" valign="top"><b><a href="etiquetas.html">Códigos de Barra libros</a><br>
			<a href="credenciales.html">Generación de credenciales</a><br>
			<a href="libre_deuda.html">Emisión de Libre Deuda</a></b></td>
  </tr>
  <tr>
   <td colspan="2" bgcolor="#FDD08E">
   		<strong>Base Bibliográfica. </strong>
   		<i>Dispare esta acción cada vez que incorpora o modifica un inventario</i>
   	</td>
  </tr>
  <tr>
	<td colspan="2">
		<form action="/cgi-bin/wxis.exe/omp/administracion/" method="post">
		<table border="0" style="valign:middle" cellpadding="8px"><tr><td>
		<input type="submit" value="Actualizar inventarios (& y @)" width="100"></td><td>Si utiliza los símbolos <b><i>&</i></b> y <b><i>@</i></b> para indicar inventarios nuevos y los que se dan de baja
		<input type="hidden" name="IsisScript" value="omp/administracion/make_InvNuevos.xis">
		</td>
		</form>
		</tr><tr>
		<td>
		<form action="/cgi-bin/wxis.exe/omp/administracion/" method="post">
		<input type="submit" value="         Actualizar inventarios         "></td><td>En caso que no utilize indicadores para inventarios nuevos y dados de baja<br> (el sistema lo detecta automáticamente)
		<input type="hidden" name="IsisScript" value="omp/administracion/actualizar_bases.xis">
		</form>
		</td></tr></table>
	</td>
	</tr>
  	<tr>	
	<td colspan="2">
		<form action="/cgi-bin/wxis.exe/omp/administracion/" method="post">
		<input type="submit" value="Control de consistencia de inventarios">
		<input type="hidden" name="IsisScript" value="omp/administracion/check_consistencia.xis">
		</form>
	</td>
    <td>	
    </td>
  </tr>
 
</table>
</body>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.html>";
}
?>
</html>