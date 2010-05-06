<?php
session_start(); 
if (isset($_SESSION["s_username"])
	&& isset($_SESSION["s_permisos"])
	&& in_array('administracion' , $_SESSION["s_permisos"])) {
?>
<html>
  <head>
    <title>Administración</title>
    <link rel="stylesheet" type="text/css" href="/omp/css/style.css">
		<style>
	select,input {vertical-align:middle;}
table td {border-width:0px; border-style:solid; border-color:#0099FF;}
	table th {border-color:white; border-style:solid; border-width:5px; -moz-border-radius:12px; padding: 5px 0px;}
	</style>
	
	<!--script language=javascript type=text/javascript src=js/popup_calendar.js-->
  </head>
  
  	<script>
		function enable_button(btn, texto){
			btn.disabled = '';
			if (texto!=null) {
				btn.value = texto;
			}
		}
		function disable_button(btn,texto){
			btn.disabled = 'disabled';
			if (texto!=null) {
				btn.value = texto;
			}
		}
		function aaa() {
				alert('hola');
		}
	</script>

  <body>
    <div id="head"> 
		<div id="title">Módulo de Administración - OPEN MarcoPolo</div>
		<div id="logo"><img src="/omp/images/logocampi.gif" width="120" height="54"></div>
    </div> 
	
	<div id="body_wrapper">
      <div id="body">
					 <div id="all">
					 			<div class="top"></div>
								<div class="content">
   
<!--###################################################-->	



<center>
 
<table border="0" width="95%">
<tr>
<th  width="50%">Calendario</th>
<th  width="50%">Esperas Vencidas</th>
</tr>
<tr>
	<td>
	<form method="POST" name="calendario_edicion" action="calendario.php" target="calendario" onSubmit="window.open('', 'calendario', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,scrollbars=yes,status=no')">

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
				<?php
				$ptr_anios_calendario = fopen("http://localhost:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_anios.xis","r");
				$anios_calendario = fread($ptr_anios_calendario,1000);
				fclose($ptr_anios_calendario);
				$anios = explode  ('~', $anios_calendario);  
				?>
			<strong align="middle">Año: </strong><select name=anio onChange="alertselected()" style="">    
				<?php
				$cant = count($anios);
				for($i=$cant-1;$i>=0;$i--) {
					echo '<option value="'.$anios[$i].'">'.$anios[$i].'</option>';
				}
				?>
			<option value=NUEVO>NUEVO</option>
		</select>
 	  	<span id="nuevo" style="display:none">
 	  	<input id="anio_nuevo" type="text" name="anio_nuevo" size="4">
 	  	</span>
	  <input id="submit" type="submit" value="Editar">
    </form>
    </td>
    <td>
	    <center>
			<form method="POST" action="cancela_esperas.php" target="esperas" onSubmit="window.open('', 'esperas', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,height=420,width=750,scrollbars=yes,status=no')">
			<input type="submit" value="Cancela Esperas Vencidas">
			</form>
		 </center>
    </td>
</tr>
<th>Políticas</th>
<th>Lectores</th>
<tr>
	<td>
	    <center>
			<form method="POST" action="politicas.php" target="politicas" onSubmit="window.open('', 'politicas', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,height=550,width=750,scrollbars=yes,status=no');">
			<input type="submit" value="Administración de Políticas">
			</form>
	    </center>
    </td>
	<td>
	    <form method="POST" action="/omp/cgi-bin/wxis.exe/omp/administracion/" style="display:inline;">
        <input type="hidden" name="IsisScript" value="administracion/abmlector.xis">
        <input type="hidden" name="opcion" value="Presentar">
        <strong>Lector: <input type="text" name="credencial" size="10">
        <input type="radio" name="id_lector" value="documento" checked>
        DNI <input type="radio" name="id_lector" value="mfn">
        MFN<br />
        <input type="submit" value="Presentar">
        </form>
        <form method="POST" action="/omp/cgi-bin/wxis.exe/omp/administracion/" style="display:inline">
        <input type="hidden" name="IsisScript" value="administracion/abmlector.xis">
        <input type="hidden" name="opcion" value="Registro Nuevo">
        <input type="hidden" name="invocado" value="menu.html">
        <input type="submit" value="Nuevo">
        </form></strong>
        </form> 
    </td>
</tr>
<tr>
    <th>Circulación Bibliográfica</th>
    <th>Otros procedimientos</th>
</tr>
<tr>
	<td align="left">
        <form name="form_listados" method="POST" action="/omp/cgi-bin/wxis.exe/omp/administracion/">
        <input type="hidden" name="IsisScript" value="administracion/listados.xis">
        <input type="Hidden" name="Orden" value="Prestamo">	
        <input type="radio" value="morosos" name="opcion">Listado de morosos<br>
        <input type="radio" value="prestamos" name="opcion">Prestamos del día (Estadística)<br>
        <input type="radio" value="id_recibos" name="opcion" checked>Devoluciones del día<br>
        <center  style="font-size : x-small;">
			Ordenamiento:
			<select onChange="window.document.form_listados.Orden.value=this.value;" style="font-size : xx-small;">
			<option value="Prestamo" selected>Nro. Papeleta</option>
			<option value="Devolucion">Cronológico</option>							
			</select>
        </center>
        <input type="radio" value="circulante" name="opcion">Prestamos en circulaci&oacute;n
		<br><br>
        <input type="submit" value="Enviar" name="B1">
        <input type="reset" value="Restablecer" name="B2"> 
        </form>
    </td>
    <td>
        <a href="etiquetas.html">Códigos de Barra libros</a><br>
		<a href="credenciales.html">Generación de credenciales</a><br>
		<a href="libre_deuda.html">Emisión de Libre Deuda</a>
    </td>
</tr>
</table>

<br /><br />
<table border="0" width="95%">    
<tr>
   <th>Base Bibliográfica. </th>
   <th>Configuración. </th>
</tr>
<tr style="vertical-align:top; text-align:center">
<td>
	<i><font size="2">Dispare esta acción cada vez que incorpora o modifica un inventario</font></i><br><br>
    <form action="/omp/cgi-bin/wxis.exe/omp/administracion/" method="post" name=actualizar_bases_form onSubmit="disable_button(document.actualizar_bases_form.submit_btn,' Realizando operación ...')">
    <input type="submit" value="Actualizar Circulación" title="Actualiza la base de datos utilizada en el sistema de circulación en función de la base bibliográfica del sistema de catalogación" name="submit_btn">
    <input type="hidden" name="IsisScript" value="administracion/actualizar_bases.xis">
	<br><br>
    <!--
	<form action="/omp/cgi-bin/wxis.exe/omp/administracion/" method="post" name=control_consistencia_form onSubmit="disable_button(document.control_consistencia_form.submit_btn)">
    <input type="submit" value="Control de consistencia de inventarios" name=submit_btn>
    <input type="hidden" name="IsisScript" value="administracion/check_consistencia.xis" >
    </form>
	<br 
    -->
 
    <form action="opac_actualiza.php" name="actualizar_opac_form" target="resultado_actualizar_opac" method="post" onsubmit="disable_button(document.actualizar_opac_form.actualizar_opac, ' Realizando operación ...')">
    <input type="submit" value="Actualizar OPAC" title="Actualiza la base de datos de libros que se muestran en el OPAC a partir de la base de datos del sistema de Catalogación" name="actualizar_opac">
    </form>
    <iframe name=resultado_actualizar_opac height=50 frameborder=0></iframe>

</td>
<?php
	include "../circulacion/json/JSON.php";
	$ptr_config = fopen("http://localhost:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/config_obtener.xis","r");
	$config_obtener = fread($ptr_config,1000);
	fclose($ptr_config);
	$json = new Services_JSON();
	$config = $json->decode($config_obtener);
?>

<td>
	<form action="/omp/cgi-bin/wxis.exe/omp/administracion/" method="post" target=resultado_grabar name=config_form onSubmit="disable_button(document.config_form.grabar)">
	<table>
		<tr><td>
				Habilitar reservas:</td>
			<td>
				<select name=reservas onChange="enable_button(document.config_form.grabar)">
					<option value="si" <?php if ($config->reservas =='si'){echo 'selected';}?>>si</option>
					<option value="no" <?php if ($config->reservas =='no'){echo 'selected';}?>>no</option>
					<br>
				</select>
			</td>
		</tr><tr>
			<td>
				Políticas de préstamo:</td>
			<td>
				<select name=politicas onChange="enable_button(document.config_form.grabar)">
					<option value="politicas" <?php if ($config->politicas=='politicas'){echo 'selected';}?>>Automático</option>
					<option value="manual" <?php if ($config->politicas=='manual'){echo 'selected';}?>>Manual</option>
					<br>
				</select>
			</td>
		</tr><tr>
			<td>
				Imprimpir papeleta:</td>
			<td>
				<select name=impresion onChange="enable_button(document.config_form.grabar)">
					<option value="si" <?php if ($config->imprimir_papeleta=='si'){echo 'selected';}?>>si</option>
					<option value="no" <?php if ($config->imprimir_papeleta=='no'){echo 'selected';}?>>no</option>
					<br>
				</select>
			</td>
		</tr>
		</tr><tr>
			<td>
				Buscar por Nro de Control (NC):</td>
			<td>
				<select name=busqueda_x_nc onChange="enable_button(document.config_form.grabar)">
					<option value="si" <?php if ($config->busqueda_x_nc=='si'){echo 'selected';}?>>si</option>
					<option value="no" <?php if ($config->busqueda_x_nc=='no'){echo 'selected';}?>>no</option>
					<br>
				</select>
			</td>
		</tr>
	</table>
	
    <input type="submit" value="         Grabar         " name=grabar disabled="disabled">
    <input type="hidden" name="IsisScript" value="administracion/config_guardar.xis"><br />
    </form>
	<iframe name=resultado_grabar height=50 frameborder=0></iframe>
    
	
</td>
</table>
</center>
<!--###################################################-->		
<br><br> 
								</div>
								<div class="bottom"></div>
						</div>
        <div class="clearer"></div>
      </div>
      <div class="clearer"></div>
    </div>
    <div id="end_body"></div>
		<div id="footer"></div>
  </body>
</html>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=/omp/login_form.php>";
}
?>

</html>

