<html>
<head>
<title>Open MarcoPolo - Edición de Calendario</title>
</head>
<body bgcolor="#E8E8D0" topmargin="0">

<?php
// recibo el parámetro $anio a ser creado/editado
$año=$_POST['anio'];
$opcion=$_POST['opcion'];

// Defino un arreglo de nombre de Meses para la visualización
$mesArray = array(
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
	$cant_dias = strlen($_datos);	
	//for($i=0;$i<$cant_dias;$i++){
	for($i=0;$i<31;$i++){
		$estilo = "width:20px;";
		if ($i%5==0)
			{$estilo .= "border-left:1px solid black;";}
		//else	{$estilo .= "border-left:1px solid gray;";	}
			
		//if ($i==30) {$estilo .= "border-right:1px solid gray;";}
		if (isset($_datos[$i])){
			if ($_datos[$i]=='0') 	{ $checked = "checked";}
								else 			{ $checked = "";}
			if (sabado_domingo($i+1,$_mes+1,$_año)){
				$estilo .= 'background-color:FBB;';
			}
			echo '<td style="'.$estilo.'"><input type=checkbox '.$checked.' name='.$_mes. '[]'.' value='.$i.'></td>';
		}
		else {
			echo '<td style="'.$estilo.'">&nbsp;</td>';		
		}
	}
}

function editar_año($_año) {
	global $mesArray;
	// Invoco al wxis con calendario_leer.xis(año) para leer los datos de un año determinado.
	$ptr_datos_año = fopen("http://127.0.0.1/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/administracion/calendario_leer.xis&anio=$_año","r");
	$datos_año = fread($ptr_datos_año,500);
	fclose($ptr_datos_año);
	// Se genera una lista de 0s y Puntos por cada mes, separada cada cadena por "~"

	// En caso que el script no encuentre el año en la BD devuelve "error".
	if (!(strpos($datos_año,"error")===false)) {
		$ptr_ultimo_año = fopen("http://127.0.0.1/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/administracion/calendario_ultimo_anio.xis","r");
		$ultimo_año = fread($ptr_ultimo_año,500);
		fclose($ptr_ultimo_año);
		echo "<font color=red>Error!: El año <b>$_año</b> no está definido.</font><br>";
		echo "El último año definido es <b>$ultimo_año</b>";
		echo '<br><br><input type=button onclick="javascript:window.close()" value="Cerrar la ventana">';
		exit;	
	}
	$meses = explode('~',$datos_año);	
	
	// Muestro el título
	echo "<h1>Edición de calendario - Año $_año</h1>";
	
	// Muestro el formulario
	echo '<style>'.
			'.dias_mes{padding:0px; width:20px; text-align=center; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
	echo "<form action=calendario_guardar.php method=post>";
	echo "<table border=0 cellspacing=0 cellpadding=0><tr><td></td>";

	// Esto muestra la numeración de los días (1..31) en la partre superior de la tabla
	for ($i=1;$i<=31;$i++){
		echo '<td class=dias_mes align=center>';
		echo $i;
		echo "</td>";
	}
	echo "</tr> ";
	//>
	
	// Recorro los 12 meses en $meses[] y muestro los checkbox.
	echo "</td>";
	
	for($i=0;$i<12;$i++) {
		echo '<tr><td width=100 height=25><b>'.$mesArray[$i+1]."</b></td>";
		mostrar_mes($i,$meses[$i],$_año);
		echo '</tr>';
	}
	
	// Esto muestra la numeración de los días (1..31) entre al final de la tabla
	echo "<tr><td></td>";
			for ($j=1;$j<=31;$j++){
				echo '<td class=dias_mes align=center>';
				echo $j;
				echo "</td>";
			}
	echo "</tr> ";
	echo "</table>";
	echo "<input type=hidden name=anio value=".$_año.">";
	echo '<br><input type=submit value=" Guardar ">';
	echo "</form>";
}

//*******************************************************************//
//********************** PARA CREAR UN NUEVO AÑO *********************//
//*******************************************************************//

function grabar_año($_año, $meses) {
	echo "Creando año $_año: ";
	$url="http://127.0.0.1/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/administracion/calendario_nuevo.xis".
			"&anio=$_año";
	
	for($i=0;$i<12;$i++){
		$url .= "&";
		$url .= $i+1;
		$url .= "=";
		$url .= $meses[$i];
	}
	//  Invoco al wxis con calendario_leer.xis(anio) para leer los datos de un año determinado.
	$ptr_grabar_datos = fopen($url,"r");
	$grabar_datos = fread($ptr_grabar_datos,500);
	fclose($ptr_grabar_datos);
	
	echo "$grabar_datos<br>";
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
	$ptr_ultimo_año = fopen("http://127.0.0.1/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/administracion/calendario_ultimo_anio.xis","r");
	$ultimo_año = fread($ptr_ultimo_año,500);
	fclose($ptr_ultimo_año);
	
	if ($año - $ultimo_año < 10) {
		if ($año>$ultimo_año) {
			for($año_actual=$ultimo_año+1;$año_actual<=$año;$año_actual++){
				crear_año($año_actual);
			}
		}
		else {
			echo "<font color=red>Error!: El año <b>$año</b> ya está definido</font><br>";
			echo "El último año definido es <b>$ultimo_año</b>.";
		}
	}
	else {
		echo "<font color=red>Error!: No es posible crear más de 10 calendarios en una operación</font><br>";
		echo "El último año definido es <b>$ultimo_año</b>.";
	}
echo '<br><br><input type=button onclick="javascript:window.close()" value="Cerrar la ventana">';
}

//*******************************************************************//



if ($opcion=='EDICION') {
	editar_año($año);
}
else {
	crear_hasta_el_año($año);
}

?>

</body>
</html>