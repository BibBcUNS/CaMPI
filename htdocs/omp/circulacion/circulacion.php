<html>
<head>
<title>CaMPI - M�dulo Circulaci�n</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>
<?php
session_start();
if (isset($_SESSION["s_username"])) {
?>
<frameset cols="25%,*" onload="moveTo(0,0)">
  <frameset rows="80%,*">
	  <frame name="indice" src="id_prestamo.php" target="principal">
	  <frame name="identificacion" src="identificacion.php" target="principal">
  </frameset>
  <frame name="principal" src="logo_prestamo.html" scrolling="auto">
  <noframes>
  <body>
  <p>Esta p�gina usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
<?php
}else{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login_form.php>";
}
?>
</html>
