<STYLE>
H1.SaltoDePagina
{
PAGE-BREAK-AFTER: always
}

.texto {font-family: Verdana, Geneva, sans-serif; font-size:2mm; font-weight:100;}
.texto2 {font-family: Verdana, Geneva, sans-serif; font-size:3mm; font-weight:100;}
@media screen {         

   .no_mostrar { display: none; }

   .no_imprimir { display: block; }

}      
@media print {

   .no_mostrar { display: block;  }

   .no_imprimir { display: none; }
}

</STYLE>


<?php
$inv_no_encontrados ='';
$cont_impresa=0;
function imprimir_etiqueta($inv,$alto,$ancho)
{
	global $inv_no_encontrados, $cont_impresa, $base;
	
	$fuente=intval($alto);
	//Si buscarsignatura.xis encuentra el inventario lo devuelve con el formato ###;###;### si no devuelve '' en $buscar_signatura
	$buscar_signatura = file_get_contents("http://localhost/catalis/cgi-bin/wxis?IsisScript=/biblio/xis/herramientas/buscar_signaturas.xis&inventario=$inv&base=$base");
	$buscar_signatura = explode(';',$buscar_signatura);
	$signatura = "";
	
	foreach($buscar_signatura as $clave=>$valor)
	{
	    $signatura = $signatura.$valor;
	}
	
	if ($signatura != '')
	{
			
	    $cont_impresa=$cont_impresa + 1;

	    print "<table border=\"0\" style=\"height: $alto"."cm; width: $ancho"."cm\"  bordercolor=\"#999999\" cellpadding=\"0\" cellspacing=\"0\" align=center>";

	    if ($base=="ucod-marc" or $base=="eunm" or $base=="allbc")
	    {
		print "<tr><td class=texto style=\"font-size: 2.5mm\"align=center height=10%><b>Universidad Nacional del Sur - BC</b></td></tr>";
	    }
	    if ($base=="agrono")
	    {
		print "<tr><td class=texto style=\"font-size: 2.5mm\" align=center height=10%><b>Biblioteca Ciencias Agrarias - UNS</b></td></tr>";
	    }
	?>
	<tr valign=top><td align=center height="50%" valign=top>
	
	<img border="0"    height="80%" width="90%" src="barcode/image.php?code=<?php print $inv;?>&style=36&type=C39&width=180&height=50&xres=1&font=5">
	</td></tr>
	
	<?php
	print "<tr><td class=texto style=\"font-size: $fuente"."mm\" align=center valign=top height=20%";
	print "<b>*".$inv."*</b></td></tr>";
	print "<tr><td class=texto style=\"font-size: $fuente"."mm\" align=center valign=top height=20%>";
	
	print "<b>".$signatura."</b>";

	print "</td></tr>";
	print "</table>";
	
	
	?>
	
	<?php
        print "<H1 class=SaltoDePagina> </H1>";
		
	}	
    else $inv_no_encontrados=$inv_no_encontrados ."-". $inv;

}
//fin funcion
?>
<html>
<head>
</head>
<body leftmargin="0" topmargin="0" bottommargin="0" >
<?php
$inventarios=$_POST['inventarios'];
$base=$_POST['base'];
$tamanio=$_POST['tamanio'];

//Genero un array a partir de los valores ingresados en el textarea "inventarios", separados por ASCII 10 o ASCII 13
$listado=preg_split('/\r\n|[\r\n]/', $inventarios);

//Creo función para hacer trim al array y borro los espacios en blanco al comienzo o al final de cada línea
function trim_value(&$value) 
{ 
    $value = trim($value); 
}

array_walk ($listado, 'trim_value');

//Todas las claves con valor NULL son eliminadas
$listado=array_filter($listado);

//Reindizo el array, sólo con las claves con valor true
$listado=array_values($listado);

//Genero un array en el que quedan con valor NULL las claves correspondientes a líneas en blanco del textarea "inventarios"
//$listado=array_filter(preg_split('/\r\n|[\r\n]/', $inventarios), 'strlen');
//Reindizo el array, sólo con las claves con valor true
//$listado=array_values($listado);


$cant_inventarios=count($listado);
//print $cant_inventarios;
$cont=0;
while ($cont < $cant_inventarios)		
{			
	$inv = $listado[$cont];
	//print "Inventario1:".$inv."<br>";
	$inv=rtrim($inv);
	$inv=ltrim($inv);
	//print "Inventario2:".$inv."<br>";
	
	$cont = $cont+1;	
	switch ($tamanio)
	{
	case "chica":
	    $alto=2.5;
	    $ancho=5;
	    break;
	case "grande":
	    $alto=4.4;
	    $ancho=5.5;
	    break;
	}
	imprimir_etiqueta($inv,$alto,$ancho);
	
}

?>

<table  border="0" class=texto2 height=115  bordercolor="#999999" cellpadding="0" cellspacing="0" align=center>
   
    <tr valing=top><td  align=center valing=top>
	<?php
		print "Etiquetas Pedidas: ".$cont;
		print "</td></tr>";
		print "<tr valing=top><td align=center valing=top>";
		print "Etiquetas Impresas: ".$cont_impresa;
		print "</td></tr>";
		print "<tr valing=top><td align=center valing=top>";
		print "Inv No encontrados: ".$inv_no_encontrados;
		print "</td></tr>";
	print "</table>";





?>
</body>
</html>
