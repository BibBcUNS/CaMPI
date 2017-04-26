<?php
	$email=$_POST['email'];
	$mensaje=$_POST['mensaje'];
	$headers = "Content-Type: text/html; charset=UTF-8\r\n";
	$headers .= "From: correo@dominio.com.ar\r\n";
	$send = mail($email,"Aviso: Libro en Espera",$mensaje,$headers);
	
	if (!$send){
		mail("correo@dominio.com.ar","Aviso: Email no enviado","No se puedo enviar el correo de reserva a $email",$headers);		
	}
	else {
		print $email;
	}	
	?>
