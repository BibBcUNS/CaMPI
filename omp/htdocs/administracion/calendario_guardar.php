<html>
<head>
<title>Open MarcoPolo - Edición de Calendario</title>
</head>
<body bgcolor="#E8E8D0" topmargin="0">

<?php

$anio = $_POST['anio'];

function cant_dias_mes($_mes, $_anio) {
	// recibe como parametro el número de mes, enero=1.
	return date("t",mktime(0,0,0,$_mes,1,$_anio));
}

function crear_cadena_mes($datos_mes, $_mes, $_anio) {
	global $SERVER_NAME;
	$cant_dias_no_habiles = count($datos_mes);
	$dias='';
	for($i=0;$i<$cant_dias_no_habiles;$i++){
		$dia_mes_no_habil = $datos_mes[$i];
		$dias[$dia_mes_no_habil]='0';
	}
	$cant_dias = cant_dias_mes($_mes+1,$_anio);
	$cadena='';
	for($i=0;$i<$cant_dias;$i++){
		if ($dias[$i]=='0') {
			$cadena .= '0';
		}
		else {
			$cadena .= '.';
		}
	}
	
	return $cadena;
}

$url="http://127.0.0.1/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_grabar.xis".
		"&anio=$anio";


for($i=0;$i<12;$i++){
	$meses[$i] = crear_cadena_mes($_POST[$i],$i,$anio);
	$url .= "&";
	$url .= $i+1;
	$url .= "=";
	$url .= $meses[$i];
}

// Invoco al wxis con calendario_leer.xis(anio) para leer los datos de un año determinado.
$ptr_grabar_datos = fopen($url,"r");
$grabar_datos = fread($ptr_grabar_datos,500);
fclose($ptr_grabar_datos);

echo $grabar_datos;
echo '<br><br><input type=button onclick="javascript:window.close()" value="Cerrar la ventana">';
?>
</body>
</html>