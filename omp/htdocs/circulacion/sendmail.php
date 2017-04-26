<?php
	$mfn=$_POST['mfn'];
	$campos_log=$_POST['campos_log'];
	
	list($dni, $inv, $operacion, $fecha, $hora, $fprestamo, $fdevolucion, $opapellido, $opnombre) = explode("~",$campos_log);
	
	$gestor= fopen($mfn.".log","w");
	fwrite($gestor,"MFN: ".$mfn."\r\n");
	fwrite($gestor,"DNI: ".$dni."\r\n");
	fwrite($gestor,"INV: ".$inv."\r\n");
	fwrite($gestor,"OPERACION: ".$operacion."\r\n");
	fwrite($gestor,"FECHA: ".$fecha."\r\n");
	fwrite($gestor,"HORA: ".$hora."\r\n");
	fwrite($gestor,"FECHA PRES: ".$fprestamo."\r\n");
	fwrite($gestor,"FECHA VENC: ".$fdevolucion."\r\n");
	fwrite($gestor,"OPERADOR: ".$opapellido.", ".$opnombre."\r\n");
	fclose($gestor);
	$message = file_get_contents($mfn.".log",true);
	mail("correo@dominio.com.ar","Movi Log CaMPI",$message);
	//unlink($mfn.".log");
	print $mfn;	
	?>
