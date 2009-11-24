<?php session_start(); ?>
<!-- La funci�n que indica los d�as que son s�bados y domingos....
hasta la versi�n de php que tengo, funsiona solo hasta el 17 de Enero de 2038 -->

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>M�dulo de Administraci�n </title>
</head>

<?php
if (isset($_SESSION["s_username"])) {
?>
<body style="background: url(/omp/images/bg.jpg) repeat-x top left;">
<br><br>
<?php
// recibo el par�metro $anio a ser creado/editado
$a�o=$_POST['anio'];
$a�o_nuevo=$_POST['anio_nuevo'];

// Defino un arreglo de nombre de Meses para la visualizaci�n
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



// Defino un arreglo de nombres de d�as.
$semanaArray = array(
							"Mon" => "Lunes",
							"Tue" => "Martes",
							"Wed" => "Miercoles",
							"Thu" => "Jueves",
							"Fri" => "Viernes",
							"Sat" => "S�bado",
							"Sun" => "Domingo"
					);
								
//*******************************************************************//
//*********************PARA EDITAR UN A�O CREADO *********************//
//*******************************************************************//
					
// Indica a que dia de la semana (Lunes, M�rtes, ...) corresponde una fecha determinada.
function dia_semana($_dia, $_mes, $_a�o) {
	$semana = date("D", mktime(0, 0, 0, $_mes, $_dia, $_a�o));
	global $semanaArray;
	return $semanaArray[$semana];
}

// Retorna True si la fecha ingresada corresponde a un s�bado o domingo, falso en caso contrario.
function sabado_domingo($_dia, $_mes, $_a�o) {
	$_dia_sem = dia_semana($_dia, $_mes, $_a�o);
	if ($_dia_sem=='S�bado' or $_dia_sem=="Domingo") {
		return true;
	}
	else {
		return false;
 	}
}

// Muestra en pantalla una lista de checkbox, uno para cada dia del mes.
// Recibe el a�o y mes en cuesti�n, y una cadena con 0s y Puntos correspondientes al mes.
function mostrar_mes ($_mes, $_datos, $_a�o) {
	global $mesArray;
	echo '<span style="border:1px solid grey;width:180px;height:205; margin=0px 0px; padding-top=8px;text-align:center">'.$mesArray[$_mes+1];
	$cant_dias = strlen($_datos);
	echo "<table border=0><tr>";
	$dia_sem = dia_semana(1, $_mes+1, $_a�o);
	
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
		case 'S�bado':
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
			if (sabado_domingo($i+1,$_mes+1,$_a�o)){
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

function editar_a�o($_a�o) {
	global $mesArray;
	global $SERVER_NAME;
	// Invoco al wxis con calendario_leer.xis(a�o) para leer los datos de un a�o determinado.
	$ptr_datos_a�o = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_leer.xis&anio=$_a�o","r");
	$datos_a�o = '';
	while (!feof($ptr_datos_a�o)) {$datos_a�o .= fread($ptr_datos_a�o, 500);}
	fclose($ptr_datos_a�o);
	
	// Se genera una lista de 0s y Puntos por cada mes, separada cada cadena por "~"

	// En caso que el script no encuentre el a�o en la BD devuelve "error".
	if (!(strpos($datos_a�o,"error")===false)) {
		$ptr_ultimo_a�o = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_ultimo_anio.xis","r");
		$ultimo_a�o = '';
		while (!feof($ptr_ultimo_a�o)) {$ultimo_a�o .= fread($ptr_ultimo_a�o, 500);}	
		fclose($ptr_ultimo_a�o);
		echo "<font color=red>Error!: El a�o <b>$_a�o</b> no est� definido.</font><br>";
		echo "El �ltimo a�o definido es <b>$ultimo_a�o</b>";
		echo '<br><br><input type=button onclick="javascript:window.close()" value="Cerrar la ventana">';
		exit;	
	}
	$meses = explode('~',$datos_a�o);	
	
	
	// Muestro el formulario
	echo '<style>'.
			'.dias_mes{padding:0px; width:20px; text-align=center; font-size:15px; font-weight:normal; height:30px}'.
			'</style>';
	echo "<form action=calendario_guardar.php method=post>";
	echo "<input type=hidden name=anio value=".$_a�o.">";
	
	// Muestro el t�tulo
	echo '<h2 style="display:inline;margin-right:245px;color:white;">Edici�n de calendario - A�o '.$_a�o.'</h2>';
	
	echo '<input type=submit value=" Guardar ">&nbsp;';
	echo '<input type=button onclick="javascript:window.close()" value="Cerrar">';
	echo '<br><br><br>';

	
	echo "<table border=0 cellspacing=0><tr>";
	for($i=0;$i<12;$i++) {
		echo '<td style="vertical-align: top">';
		mostrar_mes($i,$meses[$i],$_a�o);
		echo "</td>";
		if (($i+1)%4==0) {echo "</tr><tr>";}
	}
	echo "</tr></table>";
	
	echo "</form>";
}

//*******************************************************************//
//********************** PARA CREAR UN NUEVO A�O *********************//
//*******************************************************************//

function grabar_a�o($_a�o, $meses) {
	global $SERVER_NAME;
	$url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_nuevo.xis".
			"&anio=$_a�o";
	
	for($i=0;$i<12;$i++){
		$url .= "&";
		$url .= $i+1;
		$url .= "=";
		$url .= $meses[$i];
	}
	//  Invoco al wxis con calendario_leer.xis(anio) para leer los datos de un a�o determinado.
	$ptr_grabar_datos = fopen($url,"r");
	$grabar_datos = '';
	while (!feof($ptr_grabar_datos)) {$grabar_datos .= fread($ptr_grabar_datos, 500);}
	fclose($ptr_grabar_datos);
	
	
	//echo "$grabar_datos<br>";
}

function cant_dias_mes($_mes, $_a�o) {
	// recibe como parametro el n�mero de mes, enero=1.
	return date("t",mktime(0,0,0,$_mes,1,$_a�o));
}

function crear_cadena_mes($_mes, $_a�o) {
	$cant_dias = cant_dias_mes($_mes+1,$_a�o);
	$cadena='';
	for($i=0;$i<$cant_dias;$i++){
		if (sabado_domingo($i+1,$_mes+1,$_a�o)) {
			$cadena .= '0';
		}
		else {
			$cadena .= '.';
		}
	}	
	return $cadena;
}

function crear_a�o($_a�o_actual){
		for($i=0;$i<12;$i++) {
			$meses[$i]=crear_cadena_mes($i,$_a�o_actual);
		}
		grabar_a�o($_a�o_actual, $meses);
}

function es_int($numero){
  if (preg_match("/^(+|-)?[0-9]+$/",$numero))
    return true;
  else
    return false;
}

function crear_hasta_el_a�o($a�o){
	global $SERVER_NAME;
	$ptr_ultimo_a�o = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_ultimo_anio.xis","r");
	$ultimo_a�o = '';
	while (!feof($ptr_ultimo_a�o)) {$ultimo_a�o .= fread($ptr_ultimo_a�o, 500);}
	
	fclose($ptr_ultimo_a�o);
	
	if ($a�o - $ultimo_a�o <= 10) {
		if ($a�o>$ultimo_a�o) {
			for($a�o_actual=$ultimo_a�o+1;$a�o_actual<=$a�o;$a�o_actual++){
				crear_a�o($a�o_actual);
			}
		}
		else {
			echo "<font color=red>El a�o ya est� definido</font>";
		}
	}
	else {
		echo "<font color=red>Error!: No es posible crear m�s de 10 a�os de calendarios en una operaci�n</font><br>";
		echo "El �ltimo a�o definido es <b>$ultimo_a�o</b>.";
		echo '<br><br><input type=button onclick="javascript:window.close()" value="Cerrar la ventana">';
		exit;
	}
}

//*******************************************************************//



if ($a�o=='NUEVO') {
	crear_hasta_el_a�o($a�o_nuevo);
	editar_a�o($a�o_nuevo);
}
else {
	editar_a�o($a�o);
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