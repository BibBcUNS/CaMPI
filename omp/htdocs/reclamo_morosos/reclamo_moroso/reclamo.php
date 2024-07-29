<?php

include('config.php');

$html='
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Reclamo a morosos</title>
</head>

<body>
<table width="100%" cellpadding="2" cellspacing="0" bgcolor="#FF9966">
  <tr>
    <td width="15%" align="center"><img src="logo.jpg" border="0"></td>
    <td width="77%" rowspan="2" align="center">
			<span style="color: #000000; font-weight: bold; font-size: 36px;">Reclamo a moroso</span>
		  <marquee><font face="Verdana" color="#ff0000">Regularice su situación a la brevedad</font></marquee>
		</td>
  </tr>
  <tr>
    <td align="center" style="font-family: Verdana; font-size: 10px">Institucion XXXXX</td>
  </tr>
</table>
<h2>'.$_POST['nombre'].'</h2>
<h3>Se le recuerda que a la fecha: '.date('d/m/y').' Ud. registra el/los siguiente/s material/es fuera de término: </h3>';

foreach ($_POST['material'] as $material) {
	$html.="<p style=\"font-size:medium\">$material</p>";
}

$html.='<p style=\"font-size:medium\">Favor de pasar a la brevedad posible para regularizar esta situación.</p>
<p style=\"font-size:medium\">Muchas gracias. </p>
</body>
</html>';

set_time_limit(0);

// optionaly, you can disable display errors
error_reporting(false); // from php (generally)
define('PRINT_ERROR', false); // from XPertMailer class

// path to XPertMailer class file
require_once './XPertMailer.php';
error_reporting(E_ALL); 
require_once 'XPertMailer.php'; 

$mail = new XPertMailer(SMTP_RELAY, SMTP_SERVER); 
$mail->auth(USER, PASS); 
$mail->from(FROM,FROMDETA);
$header['Bcc'] = BCC;
$mail->headers($header);
$imgs['logo.jpg'] = 'biblio.jpg';
$mail->attach($imgs, ATTACH_HTML_IMG);

// send to multiple e-mail addresses and optionaly you can set charset value (here UTF-8, by default is ISO-8859-1)
// as you can see, the text/plain message is required because not all mail clients can currently support HTML messages
$send = $mail->send($_POST['email'], SUBJECT, 'text version'.CRLF.'new line', $html);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Reclamo Bibliográfico</title>

</head>

<body bgcolor="#E8E8D0">

<h2 align="center">Reclamo Bibliográfico</h2><hr>
<?php 
echo "<h3 align=\"center\">Usuario ".$_POST['nombre']."</h3>";
if ($send) {
	echo "<p align=\"center\">Correo enviado exitosamente a <br>".$_POST['email']."</p>";
}else{
	echo "<p><font color=\"#ff0000\" size=\"+1\">No se pudo enviar el correo a ".$_POST['email'].", el servidor ha indicado: ".$mail->response()."</font></p>";
}
?>
<a href="javascript:window.close()">Cerrar</a>


</body>
</html>