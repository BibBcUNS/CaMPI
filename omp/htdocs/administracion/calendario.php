<?php session_start(); ?>
<!-- La función que indica los días que son sábados y domingos....
hasta la versión de php que tengo, funsiona solo hasta el 17 de Enero de 2038 -->

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Módulo de Administración </title>
</head>

<?php
if (isset($_SESSION["s_username"])) {
?>
<body style="background: url(/omp/images/bg.jpg) repeat-x top left;">
<br><br>
<?php
// recibo el parámetro $anio a ser creado/editado
$año=$_POST['anio'];
$año_nuevo=$_POST['anio_nuevo'];

// Defino un arreglo de nombre de Meses para la visualización
/*$mesArray = array(
							1 => "Enero",
							2 => "Febrero",
							3 => "Marzo",
							4 => "Abril",
							5 => "Mayo",
							6 => "Junio",
							7 => "Julio",
							8 => "Agosto",
							9 => "Septiembre",
							10 => "Octubre",
							11 => "Noviembre",
							12 => "Diciembre"
					);*/
$mesArray = array(
							1 => "ENERO",
							2 => "FEBRERO",
							3 => "MARZO",
							4 => "ABRIL",
							5 => "MAYO",
							6 => "JUNIO",
							7 => "JULIO",
							8 => "AGOSTO",
							9 => "SEPTIEMBRE",
							10 => "OCTUBRE",
							11 => "NOVIEMBRE",
							12 => "DICIEMBRE"
);



// Defino un arreglo de nombres de días.
$semanaArray = array(
							"Mon" => "Lunes",
							"Tue" => "Martes",
							"Wed" => "Miercoles",
							"Thu" => "Jueves",
							"Fri" => "Viernes",
							"Sat" => "Sábado",
							"Sun" => "Domingo"
					);
								
//*******************************************************************//
//*********************PARA EDITAR UN AÑO CREADO *********************//
//*******************************************************************//
					
// Indica a que dia de la semana (Lunes, Mártes, ...) corresponde una fecha determinada.
function dia_semana($_dia, $_mes, $_año) {
	$semana = date("D", mktime(0, 0, 0, $_mes, $_dia, $_año));
	global $semanaArray;
	return $semanaArray[$semana];
}

// Retorna True si la fecha ingresada corresponde a un sábado o domingo, falso en caso contrario.
function sabado_domingo($_dia, $_mes, $_año) {
	$_dia_sem = dia_semana($_dia, $_mes, $_año);
	if ($_dia_sem=='Sábado' or $_dia_sem=="Domingo") {
		return true;
	}
	else {
		return false;
 	}
}

// Muestra en pantalla una lista de checkbox, uno para cada dia del mes.
// Recibe el año y mes en cuestión, y una cadena con 0s y Puntos correspondientes al mes.
function mostrar_mes ($_mes, $_datos, $_año) {
	global $mesArray;
	echo '<span style="border:1px solid grey;width:180px;height:205; margin=0px 0px; padding-top=8px;text-align:center">'.$mesArray[$_mes+1];
	$cant_dias = strlen($_datos);
	echo "<table border=0><tr>";
	$dia_sem = dia_semana(1, $_mes+1, $_año);
	
	switch($dia_sem){
		case 'Lunes':
			$pos=0;
			break;	
		case 'Martes':
			echo "<td></td>";
			$pos=1;
			break;
		case 'Miercoles':
			echo "<td></td><td></td>";
			$pos=2;
			break;
		case 'Jueves':
			echo "<td></td><td></td><td></td>";
			$pos=3;
			break;
		case 'Viernes':
			echo "<td></td><td></td><td></td><td></td>";
			$pos=4;
			break;
		case 'Sábado':
			echo "<td></td><td></td><td></td><td></td><td></td>";
			$pos=5;
			break;
		case 'Domingo':
			echo "<td></td><td></td><td></td><td></td><td></td><td></td>";
			$pos=6;
			break;
	}
	
	//for($i=0;$i<$cant_dias;$i++){
	for($i=0;$i<31;$i++){
		$estilo = "width:0px;height:0px;padding:0px;margin:10px;";
		if ($pos%7==0){
				echo '</tr><tr>';
		}
		$pos = $pos + 1;
		//else	{$estilo .= "border-left:1px solid gray;";	}
			
		//if ($i==30) {$estilo .= "border-right:1px solid gray;";}
		if (isset($_datos[$i])){
			if ($_datos[$i]=='0') 	{ $checked = "checked";}
								else 			{ $checked = "";}
			if (sabado_domingo($i+1,$_mes+1,$_año)){
				$estilo .= 'background-color:FBB;';
			}
			echo '<td style="'.$estilo.'"><font style="font-size:11.5px;"><center>'.($i+1).'<br><input type=checkbox style="height:13px;width:13px;margin-right:3px;margin-left:3px" '.$checked.' name='.$_mes. '[]'.' value='.$i.'><center></font></td>';
		}
		else {
			echo '<td style="'.$estilo.'">&nbsp;</td>';		
		}
	}
	echo "</table></span>";

	
}

function editar_año($_año) {
	global $mesArray;
	global $SERVER_NAME;
	// Invoco al wxis con calendario_leer.xis(año) para leer los datos de un año determinado.
	$ptr_datos_año = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_leer.xis&anio=$_año","r");
	$datos_año = '';
	while (!feof($ptr_datos_año)) {$datos_año .= fread($ptr_datos_año, 500);}
	fclose($ptr_datos_año);
	
	// Se genera una lista de 0s y Puntos por cada mes, separada cada cadena por "~"

	// En caso que el script no encuentre el año en la BD devuelve "error".
	if (!(strpos($datos_año,"error")===false)) {
		$ptr_ultimo_año = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_ultimo_anio.xis","r");
		$ultimo_año = '';
		while (!feof($ptr_ultimo_año)) {$ultimo_año .= fread($ptr_ultimo_año, 500);}	
		fclose($ptr_ultimo_año);
		echo "<font color=red>Error!: El año <b>$_año</b> no está definido.</font><br>";
		echo "El último año definido es <b>$ultimo_año</b>";
		echo '<br><br><input type=button onclick="javascript:window.close()" value="Cerrar la ventana">';
		exit;	
	}
	$meses = explode('~',$datos_año);	
	
	
	// Muestro el formulario
	echo '<style>'.
			'.dias_mes{padding:0px; width:20px; text-align=center; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
	echo "<form action=calendario_guardar.php method=post>";
	echo "<input type=hidden name=anio value=".$_año.">";
	
	// Muestro el título
	echo '<h2 style="display:inline;margin-right:245px;color:white;">Edición de calendario - Año '.$_año.'</h2>';
	
	echo '<input type=submit value=" Guardar ">&nbsp;';
	echo '<input type=button onclick="javascript:window.close()" value="Cerrar">';
	echo '<br><br><br>';

	
	echo "<table border=0 cellspacing=0><tr>";
	for($i=0;$i<12;$i++) {
		echo '<td style="vertical-align: top">';
		mostrar_mes($i,$meses[$i],$_año);
		echo "</td>";
		if (($i+1)%4==0) {echo "</tr><tr>";}
	}
	echo "</tr></table>";
	
	echo "</form>";
}

//*******************************************************************//
//********************** PARA CREAR UN NUEVO AÑO *********************//
//*******************************************************************//

function grabar_año($_año, $meses) {
	global $SERVER_NAME;
	$url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_nuevo.xis".
			"&anio=$_año";
	
	for($i=0;$i<12;$i++){
		$url .= "&";
		$url .= $i+1;
		$url .= "=";
		$url .= $meses[$i];
	}
	//  Invoco al wxis con calendario_leer.xis(anio) para leer los datos de un año determinado.
	$ptr_grabar_datos = fopen($url,"r");
	$grabar_datos = '';
	while (!feof($ptr_grabar_datos)) {$grabar_datos .= fread($ptr_grabar_datos, 500);}
	fclose($ptr_grabar_datos);
	
	
	//echo "$grabar_datos<br>";
}

function cant_dias_mes($_mes, $_año) {
	// recibe como parametro el número de mes, enero=1.
	return date("t",mktime(0,0,0,$_mes,1,$_año));
}

function crear_cadena_mes($_mes, $_año) {
	$cant_dias = cant_dias_mes($_mes+1,$_año);
	$cadena='';
	for($i=0;$i<$cant_dias;$i++){
		if (sabado_domingo($i+1,$_mes+1,$_año)) {
			$cadena .= '0';
		}
		else {
			$cadena .= '.';
		}
	}	
	return $cadena;
}

function crear_año($_año_actual){
		for($i=0;$i<12;$i++) {
			$meses[$i]=crear_cadena_mes($i,$_año_actual);
		}
		grabar_año($_año_actual, $meses);
}

function es_int($numero){
  if (preg_match("/^(+|-)?[0-9]+$/",$numero))
    return true;
  else
    return false;
}

function crear_hasta_el_año($año){
	global $SERVER_NAME;
	$ptr_ultimo_año = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_ultimo_anio.xis","r");
	$ultimo_año = '';
	while (!feof($ptr_ultimo_año)) {$ultimo_año .= fread($ptr_ultimo_año, 500);}
	
	fclose($ptr_ultimo_año);
	
	if ($año - $ultimo_año <= 10) {
		if ($año>$ultimo_año) {
			for($año_actual=$ultimo_año+1;$año_actual<=$año;$año_actual++){
				crear_año($año_actual);
			}
		}
		else {
			echo "<font color=red>El año ya está definido</font>";
		}
	}
	else {
		echo "<font color=red>Error!: No es posible crear más de 10 años de calendarios en una operación</font><br>";
		echo "El último año definido es <b>$ultimo_año</b>.";
		echo '<br><br><input type=button onclick="javascript:window.close()" value="Cerrar la ventana">';
		exit;
	}
}

//*******************************************************************//



if ($año=='NUEVO') {
	crear_hasta_el_año($año_nuevo);
	editar_año($año_nuevo);
}
else {
	editar_año($año);
}

?>
<!------------------------------------------------------------------------------------------------->  
</body>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.html>";
}
?>
</html>