<?php
header('Content-Type: text/html; charset=ISO-8859-1');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
    <title>Módulo de Administración </title>
    <link rel="stylesheet" type="text/css" href="../css/style.css" >
</head>
 <body>
    <div id="head">
		  <div id="title">Módulo de Administración - Calendario(Guardar) 
		  <div id="logo"><img src="../images/logocampi2.gif"  width="156" height="71" ></div>
		  </div>
		 
      
    </div> 
    <div id="body_wrapper">
      <div id="body">
					 <div id="all">
								<div class="top"></div>
								<div class="content">
<!------------------------------------------------------------------------------------------------->  

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
		if (isset($dias[$i]) && $dias[$i]=='0') {
			$cadena .= '0';
		}
		else {
			$cadena .= '.';
		}
	}
	
	return $cadena;
}

$url="http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/administracion/?IsisScript=administracion/calendario_grabar.xis".
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
<!------------------------------------------------------------------------------------------------->  
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