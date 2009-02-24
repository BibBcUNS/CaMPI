<?php
//Include the code
include('PHPlot.php');

//Define the object
$graph = new PHPlot(500,300); 

// ejemplo de las variables cgi, la Serie[0] define las fechas analizadas
//$Series[0]='29/7,29/7,30/7,31/7,1/8';
//$Series[1]='52,44,32,41,52';
//$Series[2]='76,32,33,24,38';
//$Series[3]='89,87,80,86,94';
//$Series[4]='11,86,101,78,56';


// Arma dos vectores, uno con los valores máximos y otro con los mínimos de cada serie de datos
// Luego establece la escala del eje y.

for ($i=1; $i<sizeof($Series); $i++) { // gira tantas veces series haya definidas
	$array_cadena=explode(',',$Series[$i]);
	$maximos[]=max($array_cadena);
	$minimos[]=min($array_cadena);
//	echo "Cadena: $Series[$i]<br>";
}

$mayor=max($maximos);
$menor=min($minimos);
$escala=intval($mayor/10);
if ($escala<1) {
	$escala=1;
	}

//	echo "Maximo: $mayor <br>";
//	echo "Minimo: $menor <br>";
//	echo "Escala: $escala <br>";


// Arma la matriz Operaciones con los valores de los movimientos 
for ($i=0; $i<sizeof($Series); $i++) { // gira tantas veces series haya definidas
	$Operaciones[$i]=explode(',',$Series[$i]);
};

// Arma la matriz Datos con los valores de los movimientos, estableciendo por cada fila la fecha y los valores para cada mov. analizado
//
// Fecha,Prestamo M,Devolucion M,Prestamo T,Devolucion T
// 29/7,19,26,62,72,
// 30/7,14,19,26,45,
// 31/7,17,10,29,35,
// 1/8,14,14,17,35,
// 2/8,7,9,23,33,

for ($i=0; $i<substr_count($Series[0],',')+1; $i++) { // gira segun la cantidad de dias analizados (contando comas de Serie[0])
	for ($j=0; $j<sizeof($Series); $j++) { 
		$Datos[$i][$j]=$Operaciones[$j][$i];
//		print($Datos[$i][$j]);
//		print('-');
	}
//	print('<br>');
};




// Leyendas y opciones gráficas

$graph->SetDataType('text-data');
$graph->SetLineWidth(1);
$graph->SetPlotType($Tipo_Grafico) ; // bars, lines, linepoints, area, points, and pie

//$graph->SetPrecisionY(0);
//$graph->SetPrecisionX(0);


$graph->SetVertTickIncrement($escala);


$graph->SetTitle("Movimientos de circulación");

//print_r($Datos);

if ($Tipo_Grafico=='pie') {
	$graph->SetShading(5);

	if (sizeof($Series)==2) { // si hay una sola operación muestra las leyendas del gráfico (referencia de los colores)
		$graph->SetLegendPixels(430,30);
		$graph->SetLegend(explode(',',$Series[0]));	
		$Pie[0]=explode(',',$Leyendas.','.$Series[1]);
		$graph->SetDataValues($Pie);
	}else{
		$graph->SetDataValues($Datos);
	}
}else{

//print_r($Series[0]);

	if (substr_count($Series[0],',')>12) {
		$graph->SetXLabelAngle(90); // si el periodo es mensual gira a 90 grados las etiquetas del eje X (fechas) 
	}
	$graph->SetShading(2);
	$graph->SetXTitle('Período');
	$graph->SetYTitle('Valores');
	$graph->SetDataValues($Datos);
}

$graph->DrawGraph(); // remember, since in this example we have one graph, PHPlot 
                     // does the PrintImage part for you 


												

?>
