<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso8859-1" />
<title>Ver Disponibilidad</title>
<link rel="stylesheet" type="text/css" href="/opacmarc/css/complete.css">
</head>
<body bgcolor="#ebebeb">
<style>
	.texto { font-family:Verdana, Geneva, sans-serif; color:#666;}
	.parte { float:left; margin:8px; font-size:11px; width:196px;height:160px; border-style:double; padding:5px; background-color:#FFF}
	.disponible {background-color: #DEF7DB}
	.consulta {color: #0F86F5;margin-top:1em;}
	.no_disponible {background-color:#FFC4C4}
</style>

<h4 class="texto" align="center">Datos Bibliogr&aacute;ficos:</h4>
<?php
//Filtrado de la entrada (FM - 23/04/2015)
$nc = filter_var(trim($_GET['nc']),FILTER_SANITIZE_STRING);

$datos=fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/marc_view.xis&nc=$nc", "r");
$datos_bibliograficos = fread($datos, 1000000);
echo "<div class='texto'>$datos_bibliograficos</div>";

//include("json/JSON.php");
?>
<h4 class="texto" align="center">Estado de los ejemplares:</h4>
<?php	
	function get_url($p_url) {
		$cadena = '';
		$ptr = fopen("http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/omp/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=circulacion/estado_ejemplares.xis&nc=".$p_url, "r");
		while (!feof($ptr)) {$cadena .= fread($ptr, 500);}
		return $cadena;
	}
	$partes_string = get_url("$nc");

	if ($partes_string){
		//$json = new Services_JSON();
		//$partes = $json->decode($partes_string);
		$partes = json_decode($partes_string);
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
			
			$consulta_cantidad = 0;
			$consulta_disponibles = 0;
			$consulta_prestados = 0;
			foreach ($parte->consulta as $ejemplar_consulta) {
			    $consulta_cantidad++;
			        if ($ejemplar_consulta->estado=='PERM'){
				    $consulta_disponibles++;
				}
			        else {
				    $consulta_prestados++;	
				}
			}
			
			$disponibles = $disponibles - $parte->esperas;  // Modificado Ricardo Piriz 24/04/2015
			$clase = ($disponibles==0?'no_disponible':'disponible');
				echo '<div class="parte texto '.$clase.'">';
			if (($disponibles) == 0)
				
				{
					
				$estado="<font color=#CC0033 ><b>No Disponible</b></font><br>";}
				else 
				{$estado="<font color=#1F8B2F ><b>Disponible</b></font><br>";}
			
			echo '<b>Tomo/Volumen: '.($parte->parte==''?'No especificado':$parte->parte).'</b><br>';
			print "$estado<br>";
			echo "Prestados: $prestados<br>";
			echo "Disponibles: $disponibles<br>";
			if (($parte->reservas =='' or  $parte->reservas ==0) && ($parte->esperas =='' or $parte->esperas ==0))
				{print "<br>No presenta esperas ni reservas";}
				else 
				 {	echo "Reservas: ".$parte->reservas."<br>";
					echo "Esperas: ".$parte->esperas."<br>";
				 }
				 
			if ($consulta_disponibles > 0) {
				echo "<div class='consulta'>Hay $consulta_disponibles ejemplare/s disponibles para consulta en la Biblioteca.</div>";
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
