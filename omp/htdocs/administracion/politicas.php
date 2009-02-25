<?php session_start(); ?>
<html>
<head>
<title>Open MarcoPolo - Edición de Políticas</title>

</head>
<?php
if (isset($_SESSION["s_username"])) {
?>
<body bgcolor="#E8E8D0" topmargin="0">

<?php
// recibo el parámetro opcion
if (isset($_POST['opcion'])) {
	$opcion=$_POST['opcion'];
}

// Defino un arreglo de nombre de Meses para la visualización
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

//********************************************************************//
//*****************MUESTRA TODAS LAS POLITICAS*******************//
//*******************************************************************//
function mostrar_politicas() {
   global $campos_nombre;
   global $SERVER_NAME;
   
	$ptr_politicas = fopen("http://$_SERVER['SERVER_NAME'];/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/politicas_obtener.xis&cual=TODAS","r");
	$politicas = fread($ptr_politicas,8192);
	fclose($ptr_politicas);

	$politicas_arreglo = explode('#',$politicas);

    for ($i=0;$i<=count($politicas_arreglo)-1;$i++){	
   	  $politicas_arreglo[$i] = explode('~',$politicas_arreglo[$i]);
	}

	// Muestro el título
	echo '<h2 style="text-align=center">Políticas de Circulación</h2>';
	
	// Muestro el formulario
	echo '<style>'.
			'.dias_mes{padding:0px; width:80px; text-align=center; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
	echo "<form action=politicas.php method=post>";
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
		echo '<tr><td width=100 height=22 align="center">
		      <input type=radio name=pol_nro value="'
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
	echo '<tr><td colspan=';
	echo $atributos_cantidad+1;
	echo ' align="center"><br>';
	echo '<input type=submit name=opcion value="Crear">';
	echo '  ';
	echo '<input type=submit name=opcion value="Modificar">';
	echo '  ';
	echo '<input type=submit name=opcion value="Borrar">';
	echo "</form>";
	echo "</td></tr>";
	echo "</table>";
}
//********************************************************************//
//***********************CREA UNA POLITICA*************************//
//*******************************************************************//
function crear_politica() {
   global $campos_nombre;

// Muestro el formulario
echo '<style>'.
			'.fila_titulo{padding:0px; width:200px; text-align=left; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
echo "<form action=politicas.php method=post>";
echo '<input type=hidden name=registro value="NUEVO">';
echo "<table border=0 cellspacing=0 cellpadding=0 align='center'>";
// Muestro el título
echo '<tr><td colspan=2><h2 style="text-align=center">Crear una nueva política de circulación</h2></td></tr>';
	
for ($i=0;$i<=(count($campos_nombre)-1);$i++)
   {
	echo "<tr><td class=fila_titulo>";
	echo $campos_nombre[$i];
	echo "</td><td><input type=text name=campo".$i."></td></tr>";
   }
	
echo '<tr><td colspan="2" align="center">';
echo '<br><input type=submit name=opcion value="Guardar">';
echo '  ';
echo '<input type=reset name=opcion value="Limpiar">';
echo "</form>";

echo "<form action=politicas.php method=post>";
echo '<input type=submit name=opcion value="Cancelar">';
echo "</form>";

echo "</td></tr>";
echo "</table>";

}	
//********************************************************************//
//***********************EDITA UNA POLITICA*************************//
//*******************************************************************//
function editar_politica() {
   global $campos_nombre;
   global $SERVER_NAME;

   $url="http://$_SERVER['SERVER_NAME'];/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/politicas_obtener.xis&cual=UNA&expresion=".$_POST['pol_nro'];

	$ptr_politicas = fopen($url,"r");
	$politicas = fread($ptr_politicas,8192);
	fclose($ptr_politicas);

	$politicas_arreglo = explode('~',$politicas);

// Muestro el formulario
echo '<style>'.
			'.fila_titulo{padding:0px; width:200px; text-align=left; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
echo "<form action=politicas.php method=post>";
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

echo "<form action=politicas.php method=post>";
echo '<input type=submit name=opcion value="Cancelar">';
echo "</form>";

echo "</td></tr>";
echo "</table>";

}

//********************************************************************//
//*********************GUARDAR UNA POLITICA***********************//
//*******************************************************************//
function guardar_politica() {
   global $campos_nombre;
   global $SERVER_NAME;

$parametros_guardar='record='.$_POST['registro'];

for ($i=0;$i<=(count($campos_nombre)-1);$i++)
   {
    $campo_actual="campo".$i;
	$parametros_guardar=$parametros_guardar."&campo".$i."=".$_POST[$campo_actual];
   }

$url="http://$_SERVER['SERVER_NAME'];/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/politicas_guardar.xis&".$parametros_guardar;
$ptr_politicas = fopen($url,"r");
$ptr_politicas;
$politicas = fread($ptr_politicas,8192);
fclose($ptr_politicas);
return $politicas;

}	

//********************************************************************//
//***********************BORRA UNA POLITICA*************************//
//*******************************************************************//
function borrar_politica() {
 $url="http://$_SERVER['SERVER_NAME'];/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/politicas_guardar.xis&record=BORRAR&expresion=".$_POST['pol_nro'];
 $ptr_politicas = fopen($url,"r");
 $politicas = fread($ptr_politicas,8192);
 fclose($ptr_politicas);
}
//*******************************************************************//

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
	else:
		 mostrar_politicas();
	endif;
}
else {
	 mostrar_politicas();
}


?>

</body>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.html>";
}
?>
</html>