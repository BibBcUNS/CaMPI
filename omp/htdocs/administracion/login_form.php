<?php session_start();?>
<html>
<head>
<title>CaMPI</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
if (isset($_SESSION["s_username"]) && $_SESSION["s_permiso"]=='administracion') {
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=menu.php>";
}else{
?>

</head>

<body>
<table  border="0" width="500" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center"><img src="../../images/campi.png" border="0"></td>
		<td align="center"><h3>Circulaci�n - M�dulo de Administraci�n</h3></td>
	</tr>
</table>

<br>
<form action='login_valida.php' method='POST'>
<table align='center' style='border:1px solid #000000;'>
<tr>
<td align='center'>
	<?php
	if (isset($_GET["error"]) && $_GET["error"]=='si'){
	echo "<br><font color='red'>Ingrese usuario y contrase&ntilde;a v&aacute;lidos</font><br><br>";
	} else {
	}
	?>
</td>
</tr>
<tr>
<td align='right'>
Nombre de usuario: <input type='text' size='15' maxlength='25' name='username'>
</td>
</tr>
<tr>
<td align='right'>
Password: <input type='password' size='15' maxlength='25' name='password'>
</td>
</tr>
<tr>
<td align='center'>
<input type="submit" value="Login">
</td>
</tr>
</table>
</form>
<table  border="0" width="500" align="center" cellpadding="0" cellspacing="0">
	<tr>
		<td align="center"><img src="../../images/abr_78.jpg" border="0"></td>
		<td align="center"><img src="../../images/ib-small-75_78.jpg" border="0"></td>
		<td align="center"><img src="../../images/inmabb-80x80_78.jpg" border="0"></td>
		<td align="center"><img src="../../images/uner_logo_78.jpg" border="0"></td>
		<td align="center"><img src="../../images/uns_logo_78.gif" border="0"></td>

	</tr>
</table>
</body>
<?php
}
?>
</html>