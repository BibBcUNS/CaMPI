<STYLE>
h1.SaltoDePagina
{
PAGE-BREAK-BEFORE: always

}

@media print {

   .no_mostrar { display: block;  }
   
   .no_imprimir { display: none; }
 
 }
 
table {
    
    border-spacing: 1pt;
    border-collapse: collapse;
}

td, th {
    padding: 1pt;
    font-size:10pt;
}
      
* {
    font-family: Verdana, Geneva, sans-serif;
}

.texto {font-family: Verdana, Geneva, sans-serif; font-size:2mm; font-weight:100;}
.texto2 {font-family: Verdana, Geneva, sans-serif; font-size:3mm; font-weight:100;}


</STYLE>

<?php
function buscar_signatura ($inv,$base){
    $buscar_signatura = file_get_contents("https://campi-catalogacion.uns.edu.ar/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/buscar_signaturas.xis&inventario=$inv&base=$base");
    $buscar_signatura = str_replace(";","",$buscar_signatura);
    return $buscar_signatura;
}

function listado_etiquetas ($listado_salida){
    print "<br><h1 align=center> Listado de Inventarios</h1>
    <table border=1 align=center>
    <thead>
    <tr>
	<th>Inventario</th>
    </tr>
    </thead>
    <tbody>";    
    for ($i = 0; $i <= count($listado_salida)-1; $i ++){
	print "<td>".$listado_salida[$i][0]."</td></tr>";
    }    
    print "</tbody></table>";
}

function revision_estanterias ($listado_salida,$sector){
    
    print "<h2 align=center> Revision de Estanterias</h2>";
    for ($i = 0; $i <= count($listado_salida)-1; $i ++){
	
	if ($i%35 == 0){
	    print "<table border=1 align=center>
		<thead>
		<tr>
		    <th>Sec</th>
		    <th>Signatura</th>
		    <th>Inventario</th>
		    <th>Ej.</th>
		    <th>Prest.</th>
		    <th>F/Etiq</th>
		    <th>F/C<br>Barra</th>
		    <th>Desord</th>
		    <th>Deter.<br>1-2-3</th>
		    <th>No<br>Esta</th>
		    <th>Verif</th>
		</tr>
		</thead>
		<tbody>";
		$header="si";
	}
	$contador=$contador+1;
	print "<tr><td>".$sector."</td>";
	print "<td>".$listado_salida[$i][1]."</td>";
	print "<td>".$listado_salida[$i][0]."</td>";
	print "<td align=center>".$listado_salida[$i][4]."</td>";
	if ($listado_salida[$i][3] == "SI") {
	    print "<td align=center>".$listado_salida[$i][3]."</td>";
	    $prestado=$prestado +1;
	}
	else {
	    print "<td align=center><input type=checkbox></td>";
	}	
	print "<td align=center><input type=\"checkbox\" name=\"etiq\"></td>";
	print "<td align=center><input type=\"checkbox\" name=\"cbarra\"></td>";
	print "<td align=center><input type=\"checkbox\" name=\"desorden\"></td>";
	print "<td align=center></td>";
	print "<td align=center><input type=\"checkbox\"></td>";
	print "<td align=center><input type=\"checkbox\"></td><tr>";
	 
	if (($i+1)%35==0 or $i+1== count($listado_salida)){
	    print "<tr bgcolor=LightGray align=center><td><b>$contador</b></td><td colspan=3><b>Subtotaltes</b></td><td align=center><b>$prestado</b></td>
	    <td></td><td></td><td></td><td></td><td></td><td></td></tr>";
	    print "</tbody></table>";
	    $header="no";
	    print "<h1 class=\"SaltoDePagina\"></h1>";
	    $prestado=0;
	    $contador=0;
	}    
    } 
    
    
}

function tabla_prestados_en_estanteria ($listado_salida){
    print "<h1 align=center> Listado PRESTADOS EN ESTANTERIA</h1>
    <table border=1 align=center>
    <thead>
	<tr>
	    <th>Inventario</th>
	    <th>Signatura</th>
	    <th>Estado</th>
	    <th>Prestado</th>
	</tr>
    </thead>
    <tbody>";

    for ($i = 0; $i <= count($listado_salida)-1; $i ++){
	if (($listado_salida[$i][2]=="OK") and ($listado_salida[$i][3]=="SI"))
	{ print "<tr bgcolor=red>";
	  print "<td>".$listado_salida[$i][0]."</td>";
	  print "<td>".$listado_salida[$i][1]."</td>";
	  print "<td>".$listado_salida[$i][2]."</td>";
	  print "<td>".$listado_salida[$i][3]."</td>";
	  print "</tr>";
	}
    }	

    print "</tbody>
    </table>";
}

function tabla_traer_sistemas ($listado_rec_no_base,$base,$listado_bloque){
    
    print "<h1 align=center> TRAER A OFICINA DE SISTEMAS</h1>
	<table border=1 align=center>
	<thead>
	    <tr>
		<th>Inventario</th>
		<th>Sig Anterior</th>
		<th>Sig Posterior</th>
	    </tr>
	</thead>
	<tbody>";	
    for ($i = 0,$size = count($listado_rec_no_base)-1;$i <= $size; $i ++){
	if (buscar_signatura($listado_rec_no_base[$i],$base) == ""){
	    print "<tr><td>".$listado_rec_no_base[$i]."</td>";
	    $ant = array_search($listado_rec_no_base[$i], $listado_bloque) -1;
	    $post = array_search($listado_rec_no_base[$i], $listado_bloque) +1;
	
	    print "<td>";
	    if ( $ant >= 0){
	    print buscar_signatura($listado_bloque[$ant],$base);
	    }
	    print "</td>";
	
	    print "<td>";
	    if ( $post < count($listado_bloque)){
	    print buscar_signatura($listado_bloque[$post],$base);
	    }
	}
	print "</td></tr>";
	
    }	
    
    print "</tbody>
    </table>";

}

function tabla_recolectados_problemas($listado_rec_no_base,$base,$listado_bloque){
    print "<h1 align=center> Recolectados con problemas</h1>
    <table border=1 align=center>
    <thead>
	<tr>
	    <th>Id</th>
	    <th>Inventario</th>
	    <th>Signatura</th>
	    <th>Sig Anterior</th>
	    <th>Sig Posterior</th>
	</tr>
    </thead>
    <tbody>";
    $k=1;
    for ($i = 0,$size = count($listado_rec_no_base)-1;$i <= $size; $i ++){
	print "<tr>";
	print "<td>".$k."</td>";
	$k=$k+1;
	print "<td>".$listado_rec_no_base[$i]."</td>";
	if (buscar_signatura($listado_rec_no_base[$i],$base) != ""){
	    print "<td>".buscar_signatura($listado_rec_no_base[$i],$base)."</td>";
	}
	else{
	    print "<td>No en Base</td>";
	}
	$ant = array_search($listado_rec_no_base[$i], $listado_bloque) -1;
	$post = array_search($listado_rec_no_base[$i], $listado_bloque) +1;
	
	print "<td>";
	if ( $ant >= 0){
	    print buscar_signatura($listado_bloque[$ant],$base);
	}
	print "</td>";
	
	print "<td>";
	if ( $post < count($listado_bloque)){
	    print buscar_signatura($listado_bloque[$post],$base);
	}
	print "</td></tr>";
	
    }	
    
    print "</tbody>
    </table>";
}

function tabla_fuera_rango ($listado_rec_no_base,$base,$listado_bloque){	
    print "<h1 align=center> Listado Fuera de Rango</h1>
    <table border=1 align=center>
    <thead>
	<tr>
	    <th>Id</th>
	    <th>Inventario</th>
	    <th>Signatura</th>
	    <th>Sig Anterior</th>
	    <th>Sig Posterior</th>
	</tr>
    </thead>
    <tbody>";
    
    $id=1;
    for ($i = 0,$size = count($listado_rec_no_base)-1;$i <= $size; $i ++)
    {
    if (buscar_signatura($listado_rec_no_base[$i],$base) != "") {
	if ((buscar_signatura($listado_rec_no_base[$i],$base) <= $sig_in) or
	    (buscar_signatura($listado_rec_no_base[$i],$base) >= $sig_out))
	    {		
	    	print "<tr><td>";
		print $id;
		$id=$id +1;		
		print "</td><td>";
		print $listado_rec_no_base[$i];
		print "</td><td>";
		print buscar_signatura($listado_rec_no_base[$i],$base);
		print "</td>";
		$ant = array_search($listado_rec_no_base[$i], $listado_bloque)-1; 
		$post = array_search($listado_rec_no_base[$i], $listado_bloque) +1;
	
		print "<td>";
		    if ( $ant >= 0){
			print buscar_signatura($listado_bloque[$ant],$base);
		    }
		print "</td>";
	
		print "<td>";
		    if ( $post < count($listado_bloque)){
			print buscar_signatura($listado_bloque[$post],$base);
		    }
		print "</td></tr>";
	} //if
    }//if
    }//for	
print "</tbody>
</table>";
}

?>

<html>
<head>
</head>
<body leftmargin="0" topmargin="0" bottommargin="0" >
<?php
ini_set('max_execution_time',300); 

$listado_bloque=$_POST['inventarios'];
$sig_in=$_POST['sig_ini'];
$sig_in_lib=$_POST['sig_ini_lib'];
$sig_out=$_POST['sig_fin'];
$sig_out_lib=$_POST['sig_fin_lib'];
$sector=$_POST['sector'];
$base=$_POST['base'];
$tipo_listado=$_POST['tipo_listado'];

$listado_salida= array();
$listado_salida_bloque= array();
$aux = "";
$listado_aux = array();
$cant_ejem_base=0;
$cant_ejem_enc=0;

/*------------------------------------------------------------------------------------------------------------------------------------------------
Se carga en $listado_base el sector, signatura, inv, y estado de prestamos de los ejemplares pedidos en el rango de Sig_in y Sig_out 
listado base para a ser un arreglo multidimensional
--------------------------------------------------------------------------------------------------------------------------------------------------*/
if ($sector !='ALL'){
$listado_base=file_get_contents("https://campi-catalogacion.uns.edu.ar/cgi-bin/wxis60?IsisScript=catalis/xis/herramientas/buscar_sig_inv_bloque.xis&base=$base&sector=$sector&sig_in=$sig_in&sig_in_lib=$sig_in_lib&sig_out=$sig_out&sig_out_lib=$sig_out_lib");
}
else {
$listado_base=file_get_contents("https://campi-catalogacion.uns.edu.ar/cgi-bin/wxis60?IsisScript=catalis/xis/herramientas/buscar_sig_inv_bloque2.xis&base=$base&sector=$sector&sig_in=$sig_in&sig_in_lib=$sig_in_lib&sig_out=$sig_out&sig_out_lib=$sig_out_lib");
}
$listado_base = explode("~",$listado_base);
array_pop($listado_base);
$cant_ejem_base= count($listado_base);
foreach ($listado_base as $clave => &$valor){

    $aux  = explode("#",$valor);
    array_push($listado_aux,$aux);
}
$listado_base = $listado_aux;
//print_r ($listado_base);





/*------------------------------------------------------------------------------------------------------
El String listado_bloque pasado desde el form se explodea y se convierte en un arreglo con sus valores limpios
-------------------------------------------------------------------------------------------------------------*/
$listado_bloque = explode(chr(13),$listado_bloque);
foreach ($listado_bloque as &$valor){
    $valor = ltrim(rtrim($valor));
}
$listado_bloque=array_values(array_filter($listado_bloque));

/*-----------------------------------------------------------------------------------------------------------------
Se recorre cada valor del $listado_base y se arma $listado_salida para preparar la presentacion en tablas
--------------------------------------------------------------------------------------------------------------------*/

$j=0;
$prestados=0;


for ($i = 0,$size = count($listado_base)-1; $i <= $size; $i++){
    //Si el inv esta prestado se suma a la variable prestados para estadistica    
    
    if ($listado_base[$i][3]=="SI" or $listado_base[$i][3]=='Si')
	{$prestados=$prestados +1;
	
    }
    
    //Se recorre el $listado_base y se comprueba si el inv esta en $listado_bloque si es asi se carga a $listado_inv_encontrados
    if (in_array($listado_base[$i][2],$listado_bloque)){
	
	$listado_salida[$i][0]=$listado_base[$i][2];
	$listado_salida[$i][1]=$listado_base[$i][1];
	$listado_salida[$i][2]="OK";
	$listado_salida[$i][3]=$listado_base[$i][3];
	$listado_salida[$i][4]=$listado_base[$i][4];
	$cant_ejem_enc = $cant_ejem_enc +1;
	$listado_inv_presentes[$j]=$listado_base[$i][2];
	$j=$j+1;
	
    }
    else {
    
	$listado_salida[$i][0]=$listado_base[$i][2];
	$listado_salida[$i][1]=$listado_base[$i][1];
	$listado_salida[$i][2]="NO ESTA";
	$listado_salida[$i][3]=$listado_base[$i][3];
    	$listado_salida[$i][4]=$listado_base[$i][4];
    }
    //print $listado_salida[$i][0];
}

$cant_ejem_base_sin_pres = $cant_ejem_base - $prestados;
$porcentaje= $cant_ejem_enc *100 /$cant_ejem_base_sin_pres;
$porcentaje= round($porcentaje,2);

//ACa se obtienen los inv del listado bloque que no se encuentran en el bloque segun la base
$listado_rec_no_base=array_values(array_diff($listado_bloque,$listado_inv_presentes));

$porcentaje_en_base = round($cant_ejem_enc*100/count($listado_bloque),2);
$porcentaje_no_en_base = round(count($listado_rec_no_base)*100/count($listado_bloque),2);

?>
<br>
<br>
<h1 align=center>Resumen de relevamiento </h1>
<table align=center>
<?php

    print "<tr><td>Sector Base: </td><td align=center> ".$sector."</td></tr>";
    print "<tr><td>Sig. Inicio: </td><td align=center> ".$sig_in." ".$sig_in_lib."</td></tr>";
    print "<tr><td>Sig. Fin: </td><td align=center>".$sig_out." ".$sig_out_lib."</td></tr>";
    print "<tr><td>Cantidad de inventarios en Bloque segun Base</td><td align=center>".count($listado_base)."</td></tr>"; 
    print "<tr><td>Cantidad de inventarios prestados en Bloque</td><td align=center>".$prestados."</td></tr>";
    print "<tr><td>Cantidad de inventarios Relevados</td><td align=center>".count($listado_bloque)."</td></tr>";
    print "<tr><td>Cantidad de inventarios Relevados presentes en Base</td><td align=center>".count($listado_inv_presentes)."</td></tr>";
    print "<tr><td>Cantidad de inventarios Relevados no presentes en Base</td><td align=center>".count($listado_rec_no_base)."</td></tr>";
    
    $total=count($listado_base)-$prestados;
    
        
    if ($sector != "SC"){
	if ($porcentaje >= 97){
	    print "<tr><td>Porcentaje de relevamiento (".$total."/".count($listado_inv_presentes).")</td><td bgcolor=green align=center> $porcentaje %</td>";
	}
	else {
	    print "<tr><td>Porcentaje de relevamiento (".$total." / ".count($listado_inv_presentes).")</td><td bgcolor=red align=center> $porcentaje %</td>";
	}
    }
    else {
	if ($porcentaje >= 90){
	    print "<tr><td>Porcentaje de relevamiento</td><td bgcolor=green align=center> $porcentaje %</td>";
	}
	else {
	    print "<tr><td>Porcentaje de relevamiento</td><td bgcolor=red align=center> $porcentaje %</td>";
	}
    }
    print "<tr><td>Porcentaje de lo relevado presente en base</td><td align=center>".$porcentaje_en_base." %</td></tr>";
    print "<tr><td>Porcentaje de lo relevado no presente en base</td><td align=center>".$porcentaje_no_en_base." %</td></tr>";
    
?>

</table>
<h1 class=SaltoDePagina></h1>

<?php

$tabla="<h1 align=center> Listado Base</h1>
<table border=1 align=center>
<thead>
    <tr>
	<th>Id</th>
	<th>Inventario</th>
	<th>Signatura</th>
	<th>Estado</th>
	<th>Prestado</th>
    </tr>
</thead>
<tbody>
";


    
    for ($i = 0; $i <= count($listado_salida)-1; $i ++){
	
	if (($listado_salida[$i][2]=="OK") and ($listado_salida[$i][3]=="SI"))
	{ $tabla=$tabla."<tr bgcolor=red>";}
	else 
	{$tabla=$tabla."<tr>";}
	$j=$i+1;
	$tabla=$tabla."<td>".$j."</td>";
	$tabla=$tabla. "<td>".$listado_salida[$i][0]."</td>";
	$tabla=$tabla. "<td>".$listado_salida[$i][1]."</td>";
	$tabla=$tabla. "<td>".$listado_salida[$i][2]."</td>";
	$tabla=$tabla. "<td>".$listado_salida[$i][3]."</td>";
	$tabla=$tabla. "</tr>";
    }	

$tabla=$tabla.
"</tbody>
</table>
<br>
";

switch ($tipo_listado) {
    case "control_stock":
	print $tabla;
	print "<h1 class=SaltoDePagina></h1>";
	tabla_prestados_en_estanteria($listado_salida);
	tabla_traer_sistemas($listado_rec_no_base,$base,$listado_bloque);
	tabla_recolectados_problemas($listado_rec_no_base,$base,$listado_bloque);
	tabla_fuera_rango($listado_rec_no_base,$base,$listado_bloque);	
	break;
    case "imprimir_etiquetas":
	listado_etiquetas($listado_salida);
	break;
    case "revision_estanterias":
	revision_estanterias($listado_salida,$sector);
	break;
}

?>


</body>
</html>


