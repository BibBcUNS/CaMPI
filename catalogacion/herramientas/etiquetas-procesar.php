<!doctype html>
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


<!--Agregado -->



<?php

$barcodeText = $_POST['inventarios'];
$base=$_POST['base'];
$barcodeType='code128';
$barcodeDisplay='horizontal';
$barcodeSize='30';
$printText='true';
$tamanio=$_POST['tamanio'];

$inv_no_encontrados ='';
$cont_impresa=0;
function imprimir_etiqueta($inv,$alto,$ancho)
{
  global $inv_no_encontrados, $cont_impresa, $base;
  
  $fuente=intval($alto);
  //Si buscarsignatura.xis encuentra el inventario lo devuelve con el formato ###;###;### si no devuelve '' en $buscar_signatura
  $buscar_signatura = file_get_contents("https://campi-catalogacion.uns.edu.ar/catalis/cgi-bin/wxis?IsisScript=catalis/xis/herramientas/buscar_signaturas.xis&inventario=$inv&base=$base");
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
      
      if ($base=="cems")
      {
    print "<tr><td class=texto style=\"font-size: 2.5mm\" align=center height=10%><b>Biblioteca del CEMS</b></td></tr>";
      }
  ?>
  <tr valign=top><td align=center height="50%" valign=top>
  <br>
  <img border="0" height="90%" width="100%" src="./barcode/barcode.php?text=<?php print $inv;?>&codetype=code128&orientation=horizontal&size=30&print=true"/>

  </td></tr>
  
<?php
 // print "<tr><td class=texto style=\"font-size: $fuente"."mm\" align=center valign=top height=30%";
 // print "<b>*".$inv."*</b></td></tr>";

  print "<tr><td class=texto style=\"font-size: $fuente"."mm\" align=center valign=top height=30%>";
  
  print "<b>".$signatura."</b>";

  print "</td></tr>";
  print "</table>";
  
  
  ?>
  
  <?php
        print "<H1 class=SaltoDePagina> </H1>";
    
  } 
    else $inv_no_encontrados=$inv_no_encontrados ."-". $inv;

}
  ?>

<!--Fin agregado -->



<html lang="en">
  <head>
  </head>
  <body>

<?php



// agregado 
$listado=preg_split('/\r\n|[\r\n]/', $barcodeText);
function trim_value(&$value) 
{ 
    $value = trim($value); 
}
array_walk ($listado, 'trim_value');
$listado=array_filter($listado);
$listado=array_values($listado);
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
		  $alto=3;
		  $ancho=4;
		  break;
	  case "grande":
		  $alto=4;
		  $ancho=5;
		  break;
  }
  imprimir_etiqueta($inv,$alto,$ancho); //esto ultimo modifique para el tamaño del codigo de barras
  
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

<!--fin agregado -->

</body>
</html>
