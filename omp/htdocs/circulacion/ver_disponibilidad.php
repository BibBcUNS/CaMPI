<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ver Disponibilidad</title>
<link rel="stylesheet" type="text/css" href="/opacmarc/css/complete.css">
</head>
<body bgcolor="#ebebeb">
<style>
	.texto { font-family:Verdana, Geneva, sans-serif; color:#666;}
	.parte { float:left; margin:8px; font-size:11px; width:196px; height:100px; border-style:double; padding:5px; background-color:#FFF}
	.disponible {background-color: #DEF7DB}
	.no_disponible {background-color:#FFC4C4}
</style>

<h4 class="texto" align="center">Datos Bibliogr&aacuteficos:</h4>
<?php
$nc = $_GET['nc'];

$datos=fopen("http://".$_SERVER["HTTP_HOST"]."/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/marc_view.xis&nc=$nc", "r");
$datos_bibliograficos = fread($datos, 1000000); 
//echo "http://".$_SERVER["HTTP_HOST"]."/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/marc_view.xis&nc=$nc";
echo "<div class='texto'>$datos_bibliograficos</div>";
//echo '<iframe src="/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/marc_view.xis&nc='.$nc.'" style="border:0px solid red; width:100%; height:120px"></iframe>';

include "c:\CaMPI\php\PEAR\go-pear-bundle\JSON.php";

?>
<h4 class="texto" align="center">Estado de los ejemplares:</h4>
<?php	
	function get_url($p_url) {
		$cadena = '';
		$ptr = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/circulacion/?".$p_url, "r");
		while (!feof($ptr)) {$cadena .= fread($ptr, 500);}
		return $cadena;
	}
	
	$partes_string = get_url("IsisScript=circulacion/estado_ejemplares.xis&nc=$nc");
	if ($partes_string){
		$json = new Services_JSON();
		$partes = $json->decode($partes_string);
		foreach($partes as $parte) {
			
			
			$prestados=0;
			$disponibles=0;
			
			foreach ($parte->ejemplares as $ejemplar) {
				//echo '<span style="color:'.($ejemplar->estado=='PERM'?'green':'red').';">'.$ejemplar->inventario.'</span>, ';
				if ($ejemplar->estado=='PERM'){
					$disponibles++;
				}
				else {
					$prestados++;	
				}
			}
			?>
 
            <?php
			$clase = ($disponibles==0?'no_disponible':'disponible');
				echo '<div class="parte texto '.$clase.'">';
			if (($disponibles) == 0)
				
				{
					
				$estado="<font color=#CC0033 ><b>No Disponible</b></font><br>";}
				else 
				{$estado="<font color=#1F8B2F ><b>Disponible</b></font><br>";}
			
			
			echo '<b>Tomo/Volumen: '.($parte->parte==''?'No especificada':$parte->parte).'</b><br>';
			print "$estado<br>";
			echo "Prestados: $prestados<br>";
			echo "Disponibles: $disponibles<br>";
			if (($parte->reservas =='' or  $parte->reservas ==0) && ($parte->esperas =='' or $parte->esperas ==0))
				{print "<br>No presenta esperas ni reservas";}
				else 
				 {	echo "Reservas: ".$parte->reservas."<br>";
					echo "Esperas: ".$parte->esperas."<br>";
				 }
			
			echo '</div>';
		}	
	}
	else {
		echo "No fue posible obtener los datos del servidor";
	}
	
?>
</body>
</html>