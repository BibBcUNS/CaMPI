﻿<?php
session_start(); 
if (isset($_SESSION["s_username"])
	&& isset($_SESSION["s_permisos"])
	&& in_array('administracion' , $_SESSION["s_permisos"])) {
?>
<html>
  <head>
    <title>CaMPI - Administración</title>
    <link rel="stylesheet" type="text/css" href="/omp/css/style.css">
		<style>
	select,input {vertical-align:middle;}
    table td {border-width:0px; border-style:solid; border-color:#0099FF;}
	table th {border-color:white; border-style:solid; border-width:5px; -moz-border-radius:12px; padding: 5px 0px;}
    table {
        /*font-size: 1.1em;*/
    }
    .tabla-configuracion select {width: 100px;}
    .tabla-configuracion tr{height: 30px}
    .plantilla_mail {width: 100%;}
    .mail_asunto,.mail_nombre {width: 100%;}
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
	</script>

  <body>
  <div id="head"> 
		<div id="title">CaMPI > Administración (OpenMarcoPolo)</div>
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
<th width="50%">Calendario</th>
<th width="50%">Esperas Vencidas</th>
</tr>
<tr>
	<td>
	<form method="POST" name="calendario_edicion" action="calendario.php" target="calendario" onSubmit="window.open('', 'calendario', 'menubar=no,locationbar=no,resizable=yes,top=0,left=0,scrollbars=yes,status=no')" >

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
				$ptr_anios_calendario = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_anios.xis","r");
				$anios_calendario = fread($ptr_anios_calendario,1000);
				fclose($ptr_anios_calendario);
				$anios = explode  ('~', $anios_calendario);  
				?>
			<strong align="middle">Año: </strong><select name=anio onChange="alertselected()" style="">    
				<?php
				$cant = count($anios);
				for($i=$cant-1;$i>=0;$i--) {
					echo '<option '.
						 'value="'.$anios[$i].'" '.
						 (date("Y")==$anios[$i]?' SELECTED ':'').
						 '>'.$anios[$i].
						 '</option>';
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
        Doc. <input type="radio" name="id_lector" value="mfn">
        MFN<br />
        <input type="submit" value="Presentar">
        </form>
        <!--form method="POST" action="/omp/cgi-bin/wxis.exe/omp/administracion/" style="display:inline">
        <input type="hidden" name="IsisScript" value="administracion/abmlector.xis">
        <input type="hidden" name="opcion" value="Registro Nuevo">
        <input type="hidden" name="invocado" value="menu.html">
        <input type="submit" value="Nuevo">
        </form--></strong>
    </td>
</tr>
<tr>
    <!--th>Circulación Bibliográfica</th-->
    <th colspan=2>Otros procedimientos</th>
</tr>
<tr>
	<!--td align="left">
        <form name="form_listados" method="POST" action="/omp/cgi-bin/wxis.exe/omp/administracion/">
        <input type="hidden" name="IsisScript" value="administracion/listados.xis">
        <input type="Hidden" name="Orden" value="Prestamo">	
        <table border="1" bordercolor="#000000" width="100%" cellpadding="2"><tr><td colspan="4">
            <input type="radio" value="morosos" name="opcion">Listado de morosos<br>
            </td></tr>
            <tr><td>
            Desde: </td><td>
            Dia:<select name=diad>
                    
                    <option value=01 selected>01</option>
                    <option value=02>02</option>
                    <option value=03>03</option>
                    <option value=04>04</option>
                    <option value=05>05</option>
                    <option value=06>06</option>
                    <option value=07>07</option>
                    <option value=08>08</option>
                    <option value=09>09</option>
                    <option value=10>10</option>
                    <option value=11>11</option>
                    <option value=12>12</option>
                    <option value=13>13</option>
                    <option value=14>14</option>
                    <option value=15>15</option>
                    <option value=16>16</option>
                    <option value=17>17</option>
                    <option value=18>18</option>
                    <option value=19>19</option>
                    <option value=20>20</option>
                    <option value=21>21</option>
                    <option value=22>22</option>
                    <option value=23>23</option>
                    <option value=24>24</option>
                    <option value=25>25</option>
                    <option value=26>26</option>
                    <option value=27>27</option>
                    <option value=28>28</option>
                    <option value=29>29</option>
                    <option value=30>30</option>
                    <option value=31>31</option>
                    </select>
                    </td><td>
                    Mes:
                    <select name=mesd>
                    <option value=01 selected>01</option>
                    <option value=02>02</option>
                    <option value=03>03</option>
                    <option value=04>04</option>
                    <option value=05>05</option>
                    <option value=06>06</option>
                    <option value=07>07</option>
                    <option value=08>08</option>
                    <option value=09>09</option>
                    <option value=10>10</option>
                    <option value=11>11</option>
                    <option value=12>12</option>
                    </select>
                    </td>
                    <td>
                    Año:
                    <select name=anod>
                    <option value=2000>2000</option>
                    <option value=2001>2001</option>
                    <option value=2002>2002</option>
                    <option value=2003>2003</option>
                    <option value=2004>2004</option>
                    <option value=2005>2005</option>
                    <option value=2006>2006</option>
                    <option value=2007>2007</option>
                    <option value=2008>2008</option>
                    <option value=2009>2009</option>
                    <option value=2010>2010</option>
                    <option value=2011>2011</option>
                    <option value=2012>2012</option>
                    <option value=2013>2013</option>
                    <option value=2014>2014</option>
                    <option value=2015>2015</option>
                    <option value=2016>2016</option>
                    <option value=2017>2017</option>
                    <option value=2018>2018</option>
                    <option value=2019 selected>2019</option>
                    <option value=2020>2020</option>
                    <option value=2021>2021</option>		    
   		    </select>
                    </td></tr>
                    <tr><td>
                    Hasta: </td><td>Dia:<select name=diaf>
                    
                    <option value=01 selected>01</option>
                    <option value=02>02</option>
                    <option value=03>03</option>
                    <option value=04>04</option>
                    <option value=05>05</option>
                    <option value=06>06</option>
                    <option value=07>07</option>
                    <option value=08>08</option>
                    <option value=09>09</option>
                    <option value=10>10</option>
                    <option value=11>11</option>
                    <option value=12>12</option>
                    <option value=13>13</option>
                    <option value=14>14</option>
                    <option value=15>15</option>
                    <option value=16>16</option>
                    <option value=17>17</option>
                    <option value=18>18</option>
                    <option value=19>19</option>
                    <option value=20>20</option>
                    <option value=21>21</option>
                    <option value=22>22</option>
                    <option value=23>23</option>
                    <option value=24>24</option>
                    <option value=25>25</option>
                    <option value=26>26</option>
                    <option value=27>27</option>
                    <option value=28>28</option>
                    <option value=29>29</option>
                    <option value=30>30</option>
                    <option value=31>31</option>
                    </select>
                    </td>
                    <td>
                    Mes:
                    <select name=mesf>
                    <option value=01 selected>01</option>
                    <option value=02>02</option>
                    <option value=03>03</option>
                    <option value=04>04</option>
                    <option value=05>05</option>
                    <option value=06>06</option>
                    <option value=07>07</option>
                    <option value=08>08</option>
                    <option value=09>09</option>
                    <option value=10>10</option>
                    <option value=11>11</option>
                    <option value=12>12</option>
                    </select>
                    </td>
                    <td>
                    Año:
                    <select name=anof>
                    <option value=2000>2000</option>
                    <option value=2001>2001</option>
                    <option value=2002>2002</option>
                    <option value=2003>2003</option>
                    <option value=2004>2004</option>
                    <option value=2005>2005</option>
                    <option value=2006>2006</option>
                    <option value=2007>2007</option>
                    <option value=2008>2008</option>
                    <option value=2009>2009</option>
                    <option value=2010>2010</option>
                    <option value=2011>2011</option>
                    <option value=2012>2012</option>
                    <option value=2013>2013</option>
                    <option value=2014>2014</option>
                    <option value=2015>2015</option>
                    <option value=2016>2016</option>
		    <option value=2017>2017</option>
                    <option value=2018>2018</option>
                    <option value=2019 selected>2019</option>
                    <option value=2020>2020</option>
                    <option value=2021>2021</option>
                    
                    </select>
        </td></tr></table>
        <input type="radio" value="prestamos" name="opcion">Préstamos del día (Estadística)<br>
        <input type="radio" value="id_recibos" name="opcion" checked>Devoluciones del día<br>
        <center  style="font-size : x-small;">
			Ordenamiento:
			<select onChange="window.document.form_listados.Orden.value=this.value;" style="font-size : xx-small;">
			<option value="Prestamo" selected>Nro. Papeleta</option>
			<option value="Devolucion">Cronológico</option>							
			</select>
        </center>
        <input type="radio" value="circulante" name="opcion">Préstamos en circulaci&oacute;n
		<br />
		<center  style="font-size : x-small;">
			Ordenamiento:
			<select name="orden" style="font-size : xx-small;">
			<option value="mfn" selected>Nº Movimiento</option>
			<option value="inv">Nº Inventario</option>
			<option value="nom">Lector</option>	
			</select>
        </center>
		<br />
        <input type="submit" value="Enviar" name="B1">
        <input type="reset" value="Restablecer" name="B2"> 
        </form>
    </td!-->
    <td>
        <a href="etiquetas.html">Códigos de Barra libros</a><br>
		<a href="credenciales.html">Generación de credenciales</a><br>
		<a href="libre_deuda.html">Emisión de Libre Deuda</a>
    </td>
    <td><form action="backup.php">
        <input type="submit" value="Backup">
    </form>
    </td>
</tr>
</table>

<br /><br />
<table border="0" width="95%">    
<tr>
   <!--th width="40%">Base Bibliográfica. </th-->
   <th width="60%">Configuración. </th>
</tr>
<tr style="vertical-align:top; text-align:center">
<!--td>
	<i><font size="2">Dispare esta acción cada vez que incorpora o modifica un inventario</font></i><br><br>

    <form action="/omp/cgi-bin/wxis.exe/omp/administracion/" method="post" name=actualizar_bases_form onSubmit="disable_button(document.actualizar_bases_form.submit_btn,' Realizando operación ...')">
    <input type="submit" value="Actualizar Circulación" title="Actualiza la base de datos utilizada en el sistema de circulación en función de la base bibliográfica del sistema de catalogación" name="submit_btn">
    <input type="hidden" name="IsisScript" value="administracion/actualizar_bases.xis">
	</form><br><br>
 
    <form action="opac_actualiza.php" name="actualizar_opac_form" target="resultado_actualizar_opac" method="post" onSubmit="disable_button(document.actualizar_opac_form.actualizar_opac, ' Realizando operación ...')">
    <input type="submit" value="Actualizar OPAC" title="Actualiza la base de datos de libros que se muestran en el OPAC a partir de la base de datos del sistema de Catalogación" name="actualizar_opac">
    </form>

    <iframe name=resultado_actualizar_opac height=50 frameborder=0></iframe>

</td-->
<?php
	//include "../circulacion/json/JSON.php";

	//$ptr_config = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/config_obtener.xis","r");
	
    //$config_obtener = fread($ptr_config,2000);
    //$config_obtener = str_replace("\r\n", '\r\n', $config_obtener);

    //fclose($ptr_config);
	//$json = new Services_JSON();
	//$config = $json->decode($config_obtener);

    $config=json_decode(str_replace("\r\n", '\r\n', file_get_contents("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/config_obtener.xis")));

?>

<td colspan="2">
	<form action="/omp/cgi-bin/wxis.exe/omp/administracion/" method="post" target=resultado_grabar name=config_form> <!-- onSubmit="disable_button(document.config_form.grabar)" -->
	<table width="100%" class="tabla-configuracion">
		<tr>
            <td width="110px">
				<select name=sistema_habilitado >
					<option value="si" <?php if ($config->sistema_habilitado =='si'){echo 'selected';}?>>SI</option>
					<option value="no" <?php if ($config->sistema_habilitado =='no'){echo 'selected';}?>>NO</option>
					<br>
				</select>
			</td>
			<td>
				Sistema habilitado
            </td>
		</tr><tr>
			<td>
				<select name=politicas >
					<option value="politicas" <?php if ($config->politicas=='politicas'){echo 'selected';}?>>Automático</option>
					<option value="manual" <?php if ($config->politicas=='manual'){echo 'selected';}?>>Manual</option>
					<br>
				</select>
			</td>
        	<td>
				Políticas de préstamo
            </td>
		</tr><tr>
			<td>
				<select name=impresion >
					<option value="no" <?php if ($config->imprimir_papeleta=='no'){echo 'selected';}?>>NO</option>
                    <option value="si" <?php if ($config->imprimir_papeleta=='si'){echo 'selected';}?>>SI</option>
					<br>
				</select>
			</td>
			<td>
				Imprimir papeleta
            </td>
		</tr><tr>
			<td>
				<select name=busqueda_x_nc >
					<option value="si" <?php if ($config->busqueda_x_nc=='si'){echo 'selected';}?>>SI</option>
					<option value="no" <?php if ($config->busqueda_x_nc=='no'){echo 'selected';}?>>NO</option>
					<br>
				</select>
			</td>
			<td>
				Buscar por Nro de Control (NC)
            </td>
		</tr><tr>
            <td>
                <select name=en_cuarentena >
                    <option value="si" <?php if ($config->en_cuarentena =='si'){echo 'selected';}?>>SI</option>
                    <option value="no" <?php if ($config->en_cuarentena =='no'){echo 'selected';}?>>NO</option>
                    <br>
                </select>
            </td>
            <td>
                Las devoluciones pasan a cuarentena.
            </td>
        </tr><tr>
            <td>
                <select name=reservas >
                    <option value="si" <?php if ($config->reservas =='si'){echo 'selected';}?>>SI</option>
                    <option value="no" <?php if ($config->reservas =='no'){echo 'selected';}?>>NO</option>
                    <br>
                </select>
            </td>
            <td>
                Reservas habilitadas.
            </td>
        </tr><tr>
            <td>
                <select name=encargar_disponible >
                    <option value="si" <?php if ($config->encargar_disponible =='si'){echo 'selected';}?>>SI</option>
                    <option value="no" <?php if ($config->encargar_disponible =='no'){echo 'selected';}?>>NO</option>
                    <br>
                </select>
            </td>
            <td>
                Encargue de material habilitado (en VuFind).
            </td>
        </tr><tr>
            <td>
                <select name=retiro_inmediato >
                    <option value="si" <?php if ($config->retiro_inmediato =='si'){echo 'selected';}?>>SI</option>
                    <option value="no" <?php if ($config->retiro_inmediato =='no'){echo 'selected';}?>>NO</option>
                    <br>
                </select>
            </td>
            <td>
                El pedido se puede retirar inmediatamente
            </td>
        </tr><tr>
            <td colspan="2"><h1 style="font-size: 1.2em;">
            Correo enviado cuando se confirma la Espera.
        </h1></td>
        </tr><tr>
            <td align="right">Asunto</td>
            <td><input type=text name="mail_asunto" class="mail_asunto"  value="<?php echo $config->mail_asunto ?>"></td>
        </tr><tr>
            <td align="right">Remitente</td>
            <td><input type=text name="mail_nombre" class="mail_nombre"  value="<?php echo $config->mail_nombre ?>"></td>
        </tr><tr>
            <td align="right" valign="top">Cuerpo</td>
            <td>
                <textarea name="plantilla_mail" class="plantilla_mail" rows=15 ><?php echo $config->plantilla_mail ?></textarea>
                <br>
                <span style="font-size: 0.8em">
                    <ul>
                    <li>[[OBJETO]] <i>será reemplazado por los datos del libro (título / autor)</i></li>
                    <li><i>No puede utilizar comillas en <b>Asunto</b> ni <b>Remitente</b></i></li>
                    </ul>
                </span>
            </td>
        </tr>
	</table>
</td>
</tr>
<tr>
<td>
	
    <input type="submit" value="         Grabar         " name=grabar ><!-- disabled="disabled"--> 
    <input type="hidden" name="IsisScript" value="administracion/config_guardar.xis"><br />
    </form>
	<iframe name=resultado_grabar height=80 frameborder=0></iframe>
    
	
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

