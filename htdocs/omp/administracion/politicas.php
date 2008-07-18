<html>
<head>
<title>Open MarcoPolo - Edición de Políticas</title>

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

function mostrar_politicas() {
   $campos_nombre[0] = 'Tipo de Usuario';
   $campos_nombre[1] = 'Tipo de Objeto';
   $campos_nombre[2] = 'Cantidad de Préstamos';
   $campos_nombre[3] = 'Días de Préstamo';
   $campos_nombre[4] = 'Reservas';
   $campos_nombre[5] = 'Espera';
   $campos_nombre[6] = 'Suspensión x dev. atrasada';
   $campos_nombre[7] = 'Suspensión x espera caida';
   $campos_nombre[8] = 'Prestar misma obra';
   
	$ptr_politicas = fopen("http://127.0.0.1/cgi-bin/wxis.exe/omp/administracion/?IsisScript=omp/administracion/obtener_politicas.xis","r");
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
	echo "<form action=politicas_abm.php method=post>";
	echo "<table border=0 cellspacing=0 cellpadding=0><tr><td></td>";

	// Esto muestra los títulos de los atributos
	for ($i=0;$i<=count($campos_nombre);$i++){
		echo '<td class=dias_mes align=center>';
		echo $campos_nombre[$i];
		echo "</td>";
	}
	echo "</tr> ";
	//>
	
	// Recorro las políticas y las muestro con un radiobutton.
	$politicas_cantidad = count($politicas_arreglo);
	$atributos_cantidad = count($politicas_arreglo[1]);
		
	for($i=0;$i<$politicas_cantidad-1;$i++) {
		echo '<tr><td width=100 height=22>
		      <input type="radio" name="pol_nro" value='
			  .$politicas_arreglo[$i][1]
			  .'~'
			  .$politicas_arreglo[$i][2]
			  .'></td>';
        for ($j=0;$j<=$atributos_cantidad;$j++) { 
            echo '<td class=dias_mes align=center>';
			echo $politicas_arreglo[$i][$j];
			echo "</td>";		
        }
		echo '</tr>';
	}
	echo '<tr><td colspan=';
	echo $atributos_cantidad+1;
	echo ' align="center">';
	echo '<input type=submit name=opcion value="Crear">';
	echo '  ';
	echo '<input type=submit name=opcion value="Modificar">';
	echo '  ';
	echo '<input type=submit name=opcion value="Borrar">';
	echo "</form>";
	echo "</td></tr>";
	echo "</table>";

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
	mostrar_politicas();
}

?>

</body>
</html>