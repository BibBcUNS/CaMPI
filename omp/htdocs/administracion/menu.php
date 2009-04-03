<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>Módulo de Administración </title>
    <link rel="stylesheet" type="text/css" href="../css/style.css" />
<!--script language=javascript type=text/javascript src=js/popup_calendar.js-->
</head>
<?php
if (isset($_SESSION["s_username"])) {
?>

<?php
$ptr_anios_calendario = fopen("http://$_SERVER[SERVER_NAME]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_anios.xis","r");
$anios_calendario = fread($ptr_anios_calendario,1000);
fclose($ptr_anios_calendario);
$anios = explode  ('~', $anios_calendario);
?>
  <body>
    <div id="head">
		  <div id="title">Módulo de Administración - OPEN MarcoPolo  
		  <div id="logo"><img src="../images/logocampi2.gif"  width="156" height="71" ></div>
		  </div>
		 
      
    </div> 
    <div id="body_wrapper">
      <div id="body">
					 <div id="all">
								<div class="top"></div>
								<div class="content">
<!------------------------------------------------------------------------------------------------->  
<center>      
<table border="0" width="90%">
    <th colspan="2">Altas y Modificaciones del Calendario</th>
	<tr>
    <td colspan="2">

	  <form method="POST" name="calendario_edicion" action="calendario.php" target="calendario" onsubmit="window.open('', 'calendario', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,scrollbars=yes,status=no')">

	  	<script>
			function alertselected(){
				index= document.calendario_edicion.anio.selectedIndex;
				anio= document.calendario_edicion.anio[index].value;
				if (anio=="NUEVO") {
					document.getElementById("nuevo").style.display="";
					document.getElementById("submit").value="Crear";
				}
				else {
					document.getElementById("nuevo").style.display="none";
					document.getElementById("submit").value="Editar";
				}
			}
		</script>
		  
	    <strong>Año: <select name=anio onChange="alertselected()">    
				<?php
					$cant = count($anios);
					for($i=$cant-1;$i>=0;$i--) {
						echo '<option value="'.$anios[$i].'">'.$anios[$i].'</option>';
					}
				?>
				<option value=NUEVO onselect="alert(hola)">NUEVO</option>
		</select></strong>
 	  	<span id="nuevo" style="display:none">
 	  	<input id="anio_nuevo" type="text" name="anio_nuevo" size="4">
 	  	</span>
	  
      
      <!--input type="radio" name="opcion" value="EDICION" checked>Editar 
	  <input type="radio" name="opcion" value="ANIONUEVO">Crear año-->
  
	  <input id="submit" type="submit" value="Editar">
      </form>
	</td>
  </tr>
    <th colspan="2">Cancela Esperas Vencidas</th>
	<tr>
    <td colspan="2">
	
	  <form method="POST" action="cancela_esperas.php" target="esperas" onsubmit="window.open('', 'esperas', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,height=420,width=750,scrollbars=yes,status=no')">
	  <input type="submit" value="Cancela Esperas Vencidas">
      </form>
	</td>
  </tr>
    <th colspan="2">Altas, Bajas y Modificaciones de Políticas de Circulación</th>
	<tr>
    <td colspan="2">
	
	  <form method="POST" action="politicas.php" target="politicas" onsubmit="window.open('', 'politicas', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,height=450,width=750,scrollbars=no,status=no')">
	  <input type="submit" value="ABM de Políticas">
      </form>
	</td>
  </tr>
    <th colspan="2">Altas y Modificaciones de Lectores</th>
  <tr>
    <td width="70%"><form method="POST" action="/omp/cgi-bin/wxis.exe/omp/administracion/">
      <input type="hidden" name="IsisScript" value="administracion/abmlector.xis">
      <input type="hidden" name="opcion" value="Presentar">
      <strong>Lector: <input type="text" name="credencial" size="10">
      <input type="radio" name="id_lector" value="documento" checked>
      DNI <input type="radio" name="id_lector" value="mfn">
      MFN&nbsp;&nbsp; <input type="submit" value="Presentar">
    </form></td>
    <td width="30%">
    <form method="POST" action="/omp/cgi-bin/wxis.exe/omp/administracion/">
      <input type="hidden" name="IsisScript" value="administracion/abmlector.xis">
      <input type="hidden" name="opcion" value="Registro Nuevo">
      <input type="hidden" name="invocado" value="menu.html">
      <input type="submit" value="Crear Nuevo Registro">
    </form></strong>
    </td>
    </form>
  </tr>
  
    <th>Circulación Bibliográfica</th>
    <th>Otros procedimientos</th>

  <tr>
    <td>
    	<form name="form_listados" method="POST" action="/omp/cgi-bin/wxis.exe/omp/administracion/">
    	 <input type="hidden" name="IsisScript" value="administracion/listados.xis">
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
   <th colspan="2">Base Bibliográfica. 
   		<i><font size="2">Dispare esta acción cada vez que incorpora o modifica un inventario</font></i>
   	</th>

  <tr>
	<td colspan="2">
		<form action="/omp/cgi-bin/wxis.exe/omp/administracion/" method="post">
		<table border="0" style="valign:middle" cellpadding="8px"><tr><td>
		<input type="submit" value="Actualizar inventarios (& y @)" width="100"></td><td>Si utiliza los símbolos <b><i>&</i></b> y <b><i>@</i></b> para indicar inventarios nuevos y los que se dan de baja
		<input type="hidden" name="IsisScript" value="administracion/make_InvNuevos.xis">
		</td>
		</form>
		</tr><tr>
		<td>
		<form action="/omp/cgi-bin/wxis.exe/omp/administracion/" method="post">
		<input type="submit" value="         Actualizar inventarios         "></td><td>En caso que no utilize indicadores para inventarios nuevos y dados de baja<br> (el sistema lo detecta automáticamente)
		<input type="hidden" name="IsisScript" value="administracion/actualizar_bases.xis">
		</form>
		</td></tr></table>
	</td>
	</tr>
  	<tr>	
	<td colspan="2">
		<form action="/omp/cgi-bin/wxis.exe/omp/administracion/" method="post">
		<input type="submit" value="Control de consistencia de inventarios">
		<input type="hidden" name="IsisScript" value="administracion/check_consistencia.xis">
		</form>
	</td>
    <td>	
    </td>
  </tr>
</table>
</center>
<!------------------------------------------------------------------------------------------------->  
								</div>
								<div class="bottom"></div>
						</div>
        <div class="clearer">
</div>
      </div>
      <div class="clearer">
	  </div>
    </div>
    <div id="end_body"></div>

		<div id="footer">Versión 0.1<br>(Julio 2008)</div>
  </body>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login_form.php>";
}
?>
</html>