<?php
//tabbertabdefault
session_start();
if (!isset($_SESSION["s_username"])) {
	echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.html>";
}
$class_tipo_lector = '';
$class_politicas = '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Módulo de Administración </title>
    <link rel="stylesheet" type="text/css" href="/omp/css/style.css" >
    <script type="text/javascript" src="/omp/administracion/tabber/tabber.js"></script>
	<link rel="stylesheet" href="/omp/administracion/tabber/tabber.css" TYPE="text/css" MEDIA="screen">
    

<script>
/*esto es para el tabber. No se que hace*/
document.write('<style type="text/css">.tabber{display:none;}<\/style>');

var anterior="";
function marcar(fila,elemento){
  document.getElementById(elemento).checked = true;
  if (anterior) anterior.style.backgroundColor='';
  fila.style.backgroundColor='#9CF';
  anterior = fila;
}

function resaltar(fila) {
	if (anterior != fila) fila.style.backgroundColor='#FFF';
}

function normal(fila) {
	if (anterior != fila) fila.style.backgroundColor='';
}

</script>

</head>
  <body>
    <div id="head"> 
		<div id="title">Módulo de Administración - OPEN MarcoPolo</div>
		<div id="logo"><img src="/omp/images/logocampi.gif" width="120" height="54"></div>
    </div>     <div id="body_wrapper">
      <div id="body">
					 <div id="all">
								<div class="top"></div>
								<div class="content">
<!--#####################################-->  



<?php
// recibo el parámetro opcion
if (isset($_POST['opcion'])) {
	$opcion=$_POST['opcion'];
}

// Defino un arreglo de nombre de de los criterios
$campos_nombre = array(
   0 => "Tipo de Usuario",
   1 => "Tipo de Objeto",
   2 => "Cantidad de Préstamos",
   3 => "Días de Préstamo",
   4 => "Reservas",
   5 => "Espera",
   6 => "Suspensión x dev. atrasada",
   7 => "Suspensión x espera caida",
   8 => "Prestar misma obra"
   );

// Defino un arreglo de nombre de de los criterios de la base Tipo_lector
$campos_nombre_TL = array(
	   0 => "Categoria ID",
	   1 => "Descripción",
	   2 => "Límite total de Préstamos",
	   3 => "Límite total de Reservas",
   );
   
//********************************************************************//
//*****************MUESTRA TODAS LAS POLITICAS*******************//
//*******************************************************************//
function mostrar_politicas() {
   global $campos_nombre, $campos_nombre_TL;
   global $class_tipo_lector, $class_politicas;

   global $SERVER_NAME;

  

    //-------------------------------------------------------
	//-------- Muestro los datos de la BD politicas ---------
	//-------------------------------------------------------
	$ptr_politicas = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/politicas_obtener.xis&cual=TODAS","r");
	$politicas = "";
	while (!feof($ptr_politicas)) {$politicas .= fread($ptr_politicas, 500);}
	fclose($ptr_politicas);

	$politicas_arreglo = explode('#',$politicas);

    for ($i=0;$i<=count($politicas_arreglo)-1;$i++){	
   	  $politicas_arreglo[$i] = explode('~',$politicas_arreglo[$i]);
	}

	echo "<div class=tabber>";

	echo "<div class='tabbertab $class_politicas'>";
	// Muestro el título	
	echo '<h2 style="text-align:left">Políticas de Circulación</h2>';
	
	// Muestro el formulario
	echo '<style>'.
			'.dias_mes{padding:0px; width:120px; text-align:center; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
	echo "<form name=form_politicas action=politicas.php method=post>";
	echo '<input type=hidden name=formulario value="politicas">';
	echo "<table border=1 cellspacing=0 cellpadding=0><tr><td></td>";

	// Esto muestra los títulos de los atributos
	for ($i=0;$i<count($campos_nombre);$i++){
		echo "<td class=dias_mes align=center>";
		echo $campos_nombre[$i];
		echo "</td>";
	}
	echo "</tr> ";
	//>
	
	// Recorro las políticas y las muestro con un radiobutton.
	$politicas_cantidad = count($politicas_arreglo);
	$atributos_cantidad = count($politicas_arreglo[0]);
		
	for($i=0;$i<$politicas_cantidad-1;$i++) {
		echo '<tr onclick="javascript:marcar(this,\'P_'.$politicas_arreglo[$i][0].'-'.$politicas_arreglo[$i][1].'\')"
				  onMouseOut="normal(this)"
				  onMouseOver="resaltar(this)"
				  class="linea">
	   		  <td width=50 height=22 align="center">
		      <input id="P_'.$politicas_arreglo[$i][0].'-'.$politicas_arreglo[$i][1].'" type=radio name=pol_nro value="'
			  .$politicas_arreglo[$i][0]
			  .'-'
			  .$politicas_arreglo[$i][1]
			  .'" checked></td>';
        for ($j=0;$j<$atributos_cantidad;$j++) { 
            echo '<td class=dias_mes align=center>';
			echo $politicas_arreglo[$i][$j];
			echo "</td>";		
        }
		echo '</tr>';
	}
	echo "</table>";
	echo '<input type=submit name=opcion value="Crear">  ';
	echo '<input type=submit name=opcion value="Modificar">  ';
	echo '<input type=submit name=opcion value="Borrar">';
	echo "</form>";
	
	echo "</div>"; // esto es del segundo tab
	
  //---------------------------------------------------------
	//-------- Muestro los datos de la BD tipo_lector ---------
	//---------------------------------------------------------

	$ptr_tipo_lector = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/tipo_lector_obtener.xis&cual=TODAS","r");
	
	$tipo_lector = "";
	while (!feof($ptr_tipo_lector)) {$tipo_lector .= fread($ptr_tipo_lector, 500);}
	fclose($ptr_tipo_lector);
	$tipo_lector_arreglo = explode('#',$tipo_lector);
    for ($i=0;$i<=count($tipo_lector_arreglo)-1;$i++){	
   	  $tipo_lector_arreglo[$i] = explode('~',$tipo_lector_arreglo[$i]);
	}
	// Muestro el título
		
	echo "<div class='tabbertab $class_tipo_lector'>";
	echo '<h2 style="text-align:left">Tipos de Lector</h2>';
	
	// Muestro el formulario
	echo "<form name=form_tipo_lector action=politicas.php method=post>";
	echo '<input type=hidden name=formulario value="tipo_lector">';
	echo "<table border=1 cellspacing=0 cellpadding=0><tr><td></td>";

	// Esto muestra los títulos de los atributos
	for ($i=0;$i<count($campos_nombre_TL);$i++){
		echo "<td class=dias_mes align=center>";
		echo $campos_nombre_TL[$i];
		echo "</td>";
	}
	echo "</tr>";
	
	
	// Recorro las políticas y las muestro con un radiobutton.
	
	$tipo_lector_cantidad = count($tipo_lector_arreglo);
	$tipo_lector_atributos_cantidad = count($tipo_lector_arreglo[0]);
		
	for($i=0;$i<$tipo_lector_cantidad-1;$i++) {
		echo '<tr onclick="javascript:marcar(this,\'TL_'.$tipo_lector_arreglo[$i][0].'\')"
				  onMouseOut="normal(this)"
				  onMouseOver="resaltar(this)"
				  class="linea">
		<td width=50 height=22 align="center">
		      <input id="TL_'.$tipo_lector_arreglo[$i][0].'" type=radio name=tipo_lector_nro value="'
			  .$tipo_lector_arreglo[$i][0]
			  .'" checked></td>';
        for ($j=0;$j<$tipo_lector_atributos_cantidad;$j++) { 
            echo '<td class=dias_mes align=center>';
			echo $tipo_lector_arreglo[$i][$j];
			echo "</td>";		
        }
		echo '</tr>';
	}
	echo "</table>";

	echo '<input type=submit name=opcion value="Crear">';
	echo '  ';
	echo '<input type=submit name=opcion value="Modificar">';
	echo '  ';
	echo '<input type=submit name=opcion value="Borrar">';
	echo "</form><br>";
	
	echo "</div>"; // esto es del tabber	
	
	
	echo "</div>"; // esto es del tabber
	
}
//********************************************************************//
//***********************CREA UNA POLITICA*************************//
//*******************************************************************//
function crear_politica() {
   global $campos_nombre;

$ptr_lista_tipos_lector = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/lista_tipos_lector.xis","r");
$lista_tipos_lector = fread($ptr_lista_tipos_lector,1000);
fclose($ptr_lista_tipos_lector);
$lista_tipos_lector = explode  ('~', $lista_tipos_lector);

// Muestro el formulario
echo '<style>'.
			'.fila_titulo{padding:0px; width:200px; text-align:left; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
echo "<form name=form_politicas action=politicas.php method=post>";
echo '<input type=hidden name=formulario value="politicas">';
echo '<input type=hidden name=registro value="NUEVO">';
echo "<table border=0 cellspacing=0 cellpadding=0 align='center'>";
// Muestro el título
echo '<tr><td colspan=2><h2 style="text-align:center">Crear una nueva política de circulación</h2></td></tr>';
	
for ($i=0;$i<=(count($campos_nombre)-1);$i++)
   {
		if ($i==0){ // Esto es lo que se aplica solo al campo "tipo lector": Se muestra la lista de tipos.
			echo "<tr><td class=fila_titulo>";
			echo $campos_nombre[$i];
			$cant_tipos = count($lista_tipos_lector);
			echo "</td><td><select name=campo".$i.">";
			$contador = 10;
			for($j=0;$j<$cant_tipos;$j++) {
				echo '<option value="'.$lista_tipos_lector[$j].'">'.$lista_tipos_lector[$j].'</option>';
			}
			echo "</select></td></tr>";
		}
		else {
			echo "<tr><td class=fila_titulo>";
			echo $campos_nombre[$i];
			echo "</td><td><input type=text name=campo".$i."></td></tr>";
		}
   }
	
echo '<tr><td colspan="2" align="center">';
echo '<br><input type=submit name=opcion value="Guardar">';
echo '  ';
echo '<input type=reset name=opcion value="Limpiar">';
echo "</form>";

echo "<form name=form_politicas action=politicas.php method=post>";
echo '<input type=hidden name=formulario value="politicas">';
echo '<input type=submit name=opcion value="Cancelar">';
echo "</form>";

echo "</td></tr>";
echo "</table>";
}

function crear_tipo_lector() {
	global $campos_nombre_TL;
	
	// Muestro el formulario
	echo '<style>'.
				'.fila_titulo{padding:0px; width:200px; text-align:left; font-size:15px; font-weight:normal; height:30px}'.
				'</style>';
	echo "<form name=form_tipo_lector action=politicas.php method=post>";
	echo '<input type=hidden name=formulario value="tipo_lector">';
	echo '<input type=hidden name=registro value="NUEVO">';
	echo "<table border=0 cellspacing=0 cellpadding=0 align='center'>";
	
	// Muestro el título
	echo '<tr><td colspan=2><h2 style="text-align=center">Crear un nuevo tipo de lector</h2></td></tr>';
		
	for ($i=0;$i<=(count($campos_nombre_TL)-1);$i++)
	   {
		echo "<tr><td class=fila_titulo>";
		echo $campos_nombre_TL[$i];
		echo "</td><td><input type=text name=campo".$i."></td></tr>";
	   }
		
	echo '<tr><td colspan="2" align="center">';
	echo '<br><input type=submit name=opcion value="Guardar">';
	echo '  ';
	echo '<input type=reset name=opcion value="Limpiar">';
	echo "</form>";
	
	echo "<form name=form_tipo_lector action=politicas.php method=post>";
	echo '<input type=hidden name=formulario value="tipo_lector">';
	echo '<input type=submit name=opcion value="Cancelar">';
	echo "</form>";
	
	echo "</td></tr>";
	echo "</table>";

}		
//********************************************************************//
//***********************EDITA UNA POLITICA*************************//
//*******************************************************************//

/***** POLITICA *****/
function editar_politica() {
   global $campos_nombre;
   global $SERVER_NAME;

   $url = "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/politicas_obtener.xis&cual=UNA&expresion=".$_POST['pol_nro'];
   
	$ptr_politicas = fopen($url,"r");
	$politicas = fread($ptr_politicas,8192);
	fclose($ptr_politicas);

	$politicas_arreglo = explode('~',$politicas);

// Muestro el formulario
echo '<style>'.
			'.fila_titulo{padding:0px; width:200px; text-align:left; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
echo "<form name=form_politicas action=politicas.php method=post>";
echo '<input type=hidden name=formulario value="politicas">';
echo '<input type=hidden name=registro value="EXISTENTE">';
echo "<table border=0 cellspacing=0 cellpadding=0 align='center'>";
// Muestro el título
echo '<tr><td colspan=2><h2 style="text-align=center">Editar una política de circulación</h2></td></tr>';
	
for ($i=0;$i<=(count($campos_nombre)-1);$i++)
   {
	echo "<tr><td class=fila_titulo>";
	echo $campos_nombre[$i];
	if ($campos_nombre[$i]=='Tipo de Usuario' or $campos_nombre[$i]=='Tipo de Objeto') {
	   echo "</td><td><input type=text name=campo".$i." value=".$politicas_arreglo[$i]." readonly='readonly'></td></tr>";
	}
	else {
       echo "</td><td><input type=text name=campo".$i." value=".$politicas_arreglo[$i]."></td></tr>";	
	}
   }
	
echo '<tr><td colspan="2" align="center">';
echo '<br><input type=submit name=opcion value="Guardar">';
echo '  ';
echo '<input type=reset name=opcion value="Limpiar">';
echo "</form>";

echo "<form name=form_politicas action=politicas.php method=post>";
echo '<input type=hidden name=formulario value="politicas">';
echo '<input type=submit name=opcion value="Cancelar">';
echo "</form>";

echo "</td></tr>";
echo "</table>";

}

/***** TIPO LECTOR *****/
function editar_tipo_lector() {
   global $campos_nombre_TL;
   global $SERVER_NAME;

   	$url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/tipo_lector_obtener.xis&cual=UNA&expresion=".$_POST['tipo_lector_nro'];
	
	$ptr_tipo_lector = fopen($url,"r");
	$tipo_lector = fread($ptr_tipo_lector,8192);
	fclose($ptr_tipo_lector);
	
	$tipo_lector_arreglo = explode('~',$tipo_lector);

// Muestro el formulario
echo '<style>'.
			'.fila_titulo{padding:0px; width:200px; text-align:left; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
echo "<form name=form_tipo_lector action=politicas.php method=post>";
echo '<input type=hidden name=formulario value="tipo_lector">';
echo '<input type=hidden name=registro value="EXISTENTE">';
echo "<table border=0 cellspacing=0 cellpadding=0 align='center'>";
// Muestro el título
echo '<tr><td colspan=2><h2 style="text-align=center">Editar un tipo de lector</h2></td></tr>';
	
for ($i=0;$i<=(count($campos_nombre_TL)-1);$i++)
   {
	echo "<tr><td class=fila_titulo>";
	echo $campos_nombre_TL[$i];
	if ($i==0) {
	   echo "</td><td><input type=text name=campo".$i." value='".$tipo_lector_arreglo[$i]."' readonly='readonly'></td></tr>";
	}
	else {
       echo "</td><td><input type=text name=campo".$i." value='".$tipo_lector_arreglo[$i]."'></td></tr>";	
	}
   }
	
echo '<tr><td colspan="2" align="center">';
echo '<br><input type=submit name=opcion value="Guardar">';
echo '  ';
echo '<input type=reset name=opcion value="Limpiar">';
echo "</form>";

echo "<form name=form_tipo_lector action=politicas.php method=post>";
echo '<input type=hidden name=formulario value="tipo_lector">';
echo '<input type=submit name=opcion value="Cancelar">';
echo "</form>";

echo "</td></tr>";
echo "</table>";
}

//********************************************************************//
//*********************GUARDAR UNA POLITICA***********************//
//*******************************************************************//


/***** POLITICA *****/
function guardar_politica() {
	global $campos_nombre;
	global $SERVER_NAME;
	global $class_politicas;

	$parametros_guardar='record='.$_POST['registro'];
	
	for ($i=0;$i<=(count($campos_nombre)-1);$i++)
	   {
		$campo_actual="campo".$i;
		$parametros_guardar=$parametros_guardar."&campo".$i."=".$_POST[$campo_actual];
	   }
	
	$url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/politicas_guardar.xis&".$parametros_guardar;
	$ptr_politicas = fopen($url,"r");
	$ptr_politicas;
	$politicas = fread($ptr_politicas,8192);
	fclose($ptr_politicas);
	$class_politicas = 'tabbertabdefault';

	return $politicas;
}
	
/***** TIPO LECTOR *****/
function guardar_tipo_lector() {
	global $campos_nombre_TL;
	global $SERVER_NAME;
	global $class_tipo_lector;
	
	$parametros_guardar='record='.$_POST['registro'];
	
	for ($i=0;$i<=(count($campos_nombre_TL)-1);$i++)
	{
		$campo_actual="campo".$i;
		$parametros_guardar=$parametros_guardar."&campo".$i."=".urlEncode($_POST[$campo_actual]);
	
	}


			$url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/tipo_lector_guardar.xis&".$parametros_guardar;
			$ptr_tipo_lector = fopen($url,"r");
			$tipo_lector = fread($ptr_tipo_lector,8192);
			
			fclose($ptr_tipo_lector);
			$class_tipo_lector = 'tabbertabdefault';
			return $tipo_lector;
	
}	

//********************************************************************//
//***********************BORRA UNA POLITICA*************************//
//*******************************************************************//
function borrar_politica() {
 $url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/politicas_guardar.xis&record=BORRAR&expresion=".$_POST['pol_nro'];
 $ptr_politicas = fopen($url,"r");
 $politicas = fread($ptr_politicas,8192);
 fclose($ptr_politicas);
}
function borrar_tipo_lector() {
 $url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/tipo_lector_guardar.xis&record=BORRAR&expresion=".$_POST['tipo_lector_nro'];
 $ptr_politicas = fopen($url,"r");
 $politicas = fread($ptr_politicas,8192);
 fclose($ptr_politicas);
}
//*******************************************************************//
/*
echo '<b>'.$_POST["formulario"].'<br>';
echo $_POST["opcion"].'</b>';
*/
if (isset($_POST["formulario"])) {
  if ($_POST["formulario"]=='politicas') {		
	  if (isset($_POST["opcion"])) {
		if ($_POST["opcion"]=='Crear'):
		  crear_politica();
		elseif($_POST["opcion"]=='Modificar'):
		  editar_politica();
		elseif($_POST["opcion"]=='Guardar'):
		  if (guardar_politica()=='CREAR_EXISTENTE') {
			echo '<h2 style="text-align=center;color:red">La identificación de la política ya existe. Cree una política nueva!</h2>';
			crear_politica();
		  }
		  else {
			mostrar_politicas();
		  }
		elseif($_POST["opcion"]=='Borrar'):
		  borrar_politica();
		  mostrar_politicas();
		endif;
	  }
  }
  if ($_POST["formulario"]=='tipo_lector') {
	  if (isset($_POST["opcion"])) {
		if ($_POST["opcion"]=='Crear'):
		  crear_tipo_lector();
		elseif($_POST["opcion"]=='Modificar'):
		  editar_tipo_lectOr();
		elseif($_POST["opcion"]=='Guardar'):
		  if (guardar_tipo_lector()=='CREAR_EXISTENTE') {
			echo '<h2 style="text-align=center;color:red">Error: Categoría ID ya existe.</h2>';
			crear_tipo_lector();
		  }
		  else
			{
			if (guardar_tipo_lector()=='ID_VACIO') {
			echo '<h2 style="text-align=center;color:red">Error: Falta Categoría ID.</h2>';
			crear_tipo_lector();
		  	}
				else
					{
					if (guardar_tipo_lector()=='DESCRIPCION') {
						echo '<h2 style="text-align=center;color:red">Error: Falta Descripción.</h2>';
						crear_tipo_lector();
						}
		  			else {
						mostrar_politicas();
		  				}
					}
			
			}

		elseif($_POST["opcion"]=='Borrar'):
		  borrar_tipo_lector();
		  mostrar_politicas();
		endif;
	  }
  }
  if ($_POST["opcion"]=='Cancelar') {
	mostrar_politicas();
  }
}
else {
    mostrar_politicas();
}
?>

<!---###################################--->  
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

			<div id="footer"></div>
</body>
</html>