	<?php
		$usuario = $_POST["usuario"];
		$pw = $_POST["password"];
	?>
	<!-- Aca comienzan las herramientas, en caso de log correcto -->
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>CaMPI Catalogación - Herramientas</title>
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

		function EsEntero(valor){
			//intento convertir a entero.
			//si era un entero no le afecta, si no lo era lo intenta convertir
			valor = parseInt(valor)

			//Compruebo si es un valor numérico
			if (isNaN(valor)) {
				//entonces (no es numero) devuelvo el valor cadena vacia
				return false
			}else{
				//En caso contrario (Si era un número) devuelvo el valor
				return true
			}
		}

		function validForm1(passForm) {

			// verificamos que se haya seleccionado una BD
			if (passForm.base.value == "") {
				alert("Debe indicar la Base de Datos sobre la que desea trabajar")
				passForm.base.focus()
				return false
			}
			// Controlamos que el campo ORIGENES no seteó vacio
			if (passForm.mfns_origenes.value == "") {
				alert("Debe indicar el/los registro/s orígen")
				passForm.base.focus();
				return false
			}

			// ORIGENES No debe contener " "
			for (i = 0; i < passForm.mfns_origenes.value.length; i++)
			{
				caracter_i = passForm.mfns_origenes.value.charAt(i);  
				if ( (caracter_i != ",") && (!EsEntero(caracter_i)) )
				{
					alert("El campo ORÍGENES contiene caracteres inválidos.\nEvite espacios");
					passForm.mfns_origenes.focus();
					return false;
				}
			}
			// Controlamos que el campo DESTINO no estÃ¡ vacio
			if (passForm.mfn_destino.value == "") {
				alert("Debe indicar el REGISTRO DESTINO")
				passForm.base.focus()
				return false
			}

			if (!EsEntero(passForm.mfn_destino.value)) {
				alert("El NÚMERO DE REGISTRO DESTINO no es un número válido")
				passForm.base.focus()
				return false
			}
			texto_de_aviso = "¿Está seguro que desea eliminar los registros \"" + passForm.mfns_origenes.value +
							 "\" en \"" + passForm.base.value + "\"?\n" +
							  "El campo v859 de dichos registros será copiados al registro \"" +
							 passForm.mfn_destino.value + "\""			
			acepta_eliminar = confirm(texto_de_aviso);
			if (acepta_eliminar) {
				passForm.submit.disabled = true;
				passForm.submit.value = 'Uniendo los registros...';
				return true;
			}
			else {
				return false;
			}			
			}
		
		function validForm2(passForm) {	
			// Verificamos que se haya seleccionado la base origen.
			if (passForm.fuente.selectedIndex < 1) {
				alert("Debe seleccionar la base ORIGEN")
				passForm.fuente.focus()
				return false
			}
			// Verificamos que se haya seleccionado la base origen.
			if (passForm.destino.selectedIndex < 1) {
				alert("Debe seleccionar la base DESTINO")
				passForm.destino.focus()
				return false
			}

			// Controlamos que el campo MFN DESTINO no setï¿½ vacio		
			if (passForm.nc_fuente.value == "") {
				alert("Debe indicar NÚMERO de registro")
				passForm.nc_fuente.focus()
				return false
			}

			// Controlamos que el NC sea un nï¿½mero.
			if (!EsEntero(passForm.nc_fuente.value)) {
				alert("El NÚMERO de registro no es válido")
				passForm.nc_fuente.focus()
				return false
			}
			texto_de_aviso = "¿Está seguro que desea eliminar el registro \"" + passForm.nc_fuente.value +
							 "\" en la base fuente?\n" +
							  "El Registro será movido a la base Destino"			
			acepta_eliminar = confirm(texto_de_aviso);
			if (acepta_eliminar) {
				passForm.submit.disabled = true;
				passForm.submit.value = 'Moviendo el registro...';
				return true;
			}
			else {
				return false;
			}			
		}

	</script>

	</head>
	<body>

		<nav id="navHerramientas">
			<h1>CaMPI Catalogación - Herramientas</h1>

			<div id="quickAccessWrapper">
      			<div id="goToLabel">Acceso rápido:</div>
      			<div title="Ir a Catalis..." id="goToCatalis" class="goToButton">Catalis</div>
      			<div title="Ir a Catauto..." id="goToCatauto" class="goToButton">Catauto</div>
    		</div>

			<div id="closeSessionWrapper" style="font-size: 19px;">
				<div>Usuario: <span><?php echo($_POST['usuario']) ?></span> </div>
				<button id="btnFinSesion">Cerrar Sesión</button>
			</div>
		</nav>

		<div id="herramientasContainer">

			<!---------------------------------------------------------------------------------------------------- 
											Sección descarga de Base de Datos 
			----------------------------------------------------------------------------------------------------->
			<div id="divDescargaBase">
				<form onsubmit="return validForm(this)" action="downloads/procesar.php" method="post">
					<h2>Descarga de Base de Datos </h2>
					<p>Base:    
					<select id="selectBase" name="base">
						<?php
							$basesxis = file_get_contents("https://campi-catalogacion.uns.edu.ar/catalis/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/bases.xis&usuario=$usuario");
							$bases = explode(":", $basesxis);
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
						<input class="btnHerramientas" type="submit" value="Bajar">
				</form>
			</div>

		<!-- ------------------------------------------------------------------------------------------------
											Impresion de etiquetas
			------------------------------------------------------------------------------------------------->

			<div id="divImpresionEtiquetas">
			<h2>Impresión de Etiquetas</h2>
			<form onsubmit="return validForm(this)" action="etiquetas-procesar.php" method="post">
				<p>Seleccione la Base de datos de la cual quiere Imprimir etiquetas:<br> <br>
					<select name=base>
						<?php
							$basesxis = file_get_contents("https://campi-catalogacion.uns.edu.ar/catalis/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/bases.xis&usuario=$usuario");
							$bases = explode(":", $basesxis);
							for($i=0; $i<count($bases)-1; $i++){
								echo "<option value=$bases[$i]>$bases[$i]</option>";
							}
						?>
					</select>
				</p>	
				<p>
				Ingrese los n&uacute;meros de inventario:<br>
				(Uno debajo del otro, al final no dejar espacios)<br> <br>
				<textarea name="inventarios" rows="10" style="text-align: center;">
				</textarea>
				</p>
				</td></tr>	
				<tr><td>
				<h3> Tamaño de la Etiqueta </h3>
				<input type="radio" name="tamanio" value="chica"> 2,5 cm (alto) x 5,0 cm (ancho) <br>
				<input type="radio" name="tamanio" value="grande" checked="checked"> 4,4 cm (alto) x 5,5 cm (ancho) <br>	
				<p><input class="btnHerramientas" type="submit" value="   Generar  Etiquetas    "></p>
				</td></tr>	
			</form>
		</div>


		<!-------------------------------------------------------------------------------------------------
												Control de stock
		-------------------------------------------------------------------------------------------------->

		<!--div id="divControlStock">
		<form onsubmit="return validForm(this)" action="control_stock.php" method="post">
			<h2>Control de Stock y Generación de Etiquetas</h2><br>
			Seleccione la Base de datos de la cual quiere generar el listado <br> 

			<select name=base>
				<?php
				/*	$basesxis = file_get_contents("https://campi-catalogacion.uns.edu.ar/catalis/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/bases.xis&usuario=$usuario");
					$bases = explode(":", $basexis);
					for($i=0; $i<count($bases)-1; $i++){
						echo "<option value=$bases[$i]>$bases[$i]</option>";
					} */
				?>
			</select><br><br>
				
			<table>
			<tr><td align="center">
			Ingrese los n&uacute;meros de inventario: <br>
			(Uno debajo del otro, al final no dejar espacios) <br>
			<br>
				<textarea name="inventarios" rows="10" style="text-align: center;">
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
			<tr>
				<td colspan="2" style="text-align: left; padding-left: 190px;">
					<p>
						Signatura Inicio: <input type="text" name="sig_ini" size="4">     
						Librística Inicio: <input type="text" name="sig_ini_lib" size="4"><br><br>
						Signatura Final: <input style="margin-left: 5px;" type="text" name="sig_fin" size="4">
						Librística Final: <input type="text" name="sig_fin_lib" size="4" style="margin-left: 4px;">
					</p>
				</td>
			</tr>
			</table>
			<h3>Seleccione el tipo de listado que desea generar</h3>
			<div id="divTipoListado">
				<input id="imprimirEtiquetas" type="radio" name="tipo_listado" value="imprimir_etiquetas"><label for="imprimirEtiquetas">Impresión de Etiquetas</label><br>
				<input id="revisionEstanterias" type="radio" name="tipo_listado" value="revision_estanterias"><label for="revisionEstanterias">Revisión de estanterías</label><br>
				<input id="controlStock" type="radio" name="tipo_listado" value="control_stock"><label for="controlStock">Control de Stock</label><br> <br>
			</div>
			<input class="btnHerramientas" type="submit" value="   Comprobar    ">
		</form>
		</div-->

<!-- UNIÓN DE REGISTROS -->

	<div id="divUnionRegistros">
		<form onsubmit="return validForm1(this)" action="unir_registros_do.php" method="post">
			<h2>Unión de registros</h2>
			<input type="hidden" name="usuario" value="<?php print $usuario ?>">
			<input type="hidden" name="pw" value="<?php print $pw ?>">
			<table border="0" cellspacing="10" align="center" width="100%">
				<tbody><tr><td align="right">Seleccione la BD</td><td align="left" width="55%">
				<select name="base">
					<?php
						echo '<option selected value=""></option>';

						$basexis = file_get_contents("https://campi-catalogacion.uns.edu.ar/catalis/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/bases.xis&usuario=$usuario");
						$bases = explode(":",$basesxis);
						for($i=0;$i<count($bases)-1;$i++){
							// Esto es una restricción QUE HAY QUE BORRAR
							if ($bases[$i] == "eunm" || $bases[$i] == "ucod-marc" || $bases[$i] == "carpc" || $bases[$i] == "bibadm" || $bases[$i] == "bibeco" || $bases[$i] == "cems" || $bases[$i] == "demo")
							echo "<option value=$bases[$i]>$bases[$i]</option>";
						}
					?>
				</select>
				</td></tr>
				<tr><td align="right">Registros a ser movidos (borrados!)</td>
				<td align="left">
					<input type="text" name="mfns_origenes" size="30"><font face="Arial, Helvetica, sans-serif" size="-1">  (separados por coma)</font>
				</td></tr>
				<tr><td align="right">Mover v859 a </td>
				<td align="left">
					<input type="text" name="mfn_destino" size="4">
				</td></tr>
				<tr align="center"><td colspan="2"><input class="btnHerramientas" type="submit" name="submit" value="Unir registros"></td></tr>
				</tbody>
			</table>
		</form>	
	</div>

<!-- MOVER REGISTROS -->

	<div id="divMoverRegistro">
		<form onsubmit="return validForm2(this)" action="ucod_2_eunm_do.php" method="post">
			<input type="hidden" name="usuario" value="<?php print $usuario ?>">
			<input type="hidden" name="pw" value="<?php print $pw ?>">
			
			<table border="0" cellspacing="10" align="center">
			<tbody><h2>Mover Registro</h2>
			<tr><td>
				Base 
				<select name="fuente">
					<?php
						echo '<option selected value=""></option>';
						for($i=0;$i<count($bases)-1;$i++){
							// Esto es una restricción QUE HAY QUE BORRAR
							if ($bases[$i] == "ucod-marc"  || $bases[$i] == "bibadm" || $bases[$i] == "bibeco" || $bases[$i] == "eunm" || $bases[$i] == "huber" || $bases[$i] == "demo")
							echo "<option value=$bases[$i]>$bases[$i]</option>";
						}
					?>
				</select>
				Número de registro <input type="text" name="nc_fuente" size="6">
			</td></tr>
			<tr><td>
				Moverlo a <span style="font-size: 22px;">➜</span>
				<select name="destino">
					<?php
						echo '<option selected value=""></option>';		
						for($i=0; $i<count($bases)-1; $i++){
							// Esto es una restricción QUE HAY QUE BORRAR
							if ($bases[$i] == "eunm" || $bases[$i] == "ead" || $bases[$i] == "eeo" || $bases[$i] == "eunm-ebook"  || $bases[$i] == "ucod-marc" || $bases[$i] == "demo")
							echo "<option value=$bases[$i]>$bases[$i]</option>";
						}
					?>
				</select>
			
			</td></tr>
			<tr align="center"><td>
				<b><input class="btnHerramientas" type="submit" name="submit" value="Mover registro"></b></td></tr>
			</tbody></table>
		</form>	
	
		</div> 

	</div>
	<!-- fin container -->

	<script>
		document.getElementById("btnFinSesion").addEventListener("click", function(){
			if ( confirm("¿Confirma que desea finalizar la sesión?") ) {
				window.location.href = "../login/php/logout.php?modulo=herramientas";
			}
		});

		document.getElementById("goToCatalis").addEventListener("click", function(){
			window.open("/login/php/openModule.php?modulo=catalis", "_blank")
		});

		document.getElementById("goToCatauto").addEventListener("click", function(){
			window.open("/login/php/openModule.php?modulo=catauto", "_blank")
		});
	</script>
</body>
</html>
