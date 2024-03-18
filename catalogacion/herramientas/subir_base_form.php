<?
// setCookie de la cookie con usuario y pw
if ($usuario<>'') {
		setcookie("userId",$usuario);
		$userId = $usuario;
}
if ($pw<>'') {
		setcookie("userPw",$pw);
		$userPw = $pw;
}
// fin setCookie

$verificar = file_get_contents("http://127.0.0.1/cgi-car/wxis.exe?IsisScript=catalis/verificarpw.xis&usuario=$userId&pw=$userPw");

if ($verificar != 'OK') {
?>  <!-- Esto es si ingresa mal la contraseña o usuario -->
	<HTML><HEAD><TITLE>Redireccionado</TITLE>
	<!--META HTTP-EQUIV="Refresh" CONTENT="0; URL=/catalis"-->
	</HEAD><BODY></BODY></HTML>
<?
}
else {
?>
	<!-- Aca comienzan las herramientas, en caso de log correcto -->
	<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Descarga de bases de Catalis</title>
	<link rel="stylesheet" type="text/css" href="http://inmabb.criba.edu.ar/catalis/catalis.css">
	</head>
	<body>
<center>
	<!-- seleccion de la base -->
	<form onsubmit="return validForm(this)" action="subir_base_do.php" method="post" style="width: 75%;
		background: brown;
		border: 1px solid #F0F0F0;
		padding: 6px;
		margin: 6px 0;
		color: #F0F0F0;
		font-size: 16px;
		font:Verdana, Arial, Helvetica, sans-serif">
	Base: 
	<select name=base>
		<?
		$basesxis = file_get_contents("http://127.0.0.1/cgi-car/wxis.exe?IsisScript=catalis/bases.xis&usuario=$userId");
		$bases = explode(":",$basesxis);
		for($i=0;$i<count($bases)-1;$i++){
			echo "<option value=$bases[$i]>$bases[$i]</option>";}
		?>
	</select> 
	
	<INPUT type="file" name="archivo" id="texto" size="40"><br>
	<br>
	<input type="submit" value="         S u b i r         ">
	</form>
</body>
</html>

<? } ?>
