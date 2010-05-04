<?php session_start(); 
if (isset($_SESSION["s_username"])
	&& isset($_SESSION["s_permisos"])
	&& in_array('circulacion' , $_SESSION["s_permisos"])) {
?>
<html>
<head>
<title>CaMPI - Módulo Circulación</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<frameset cols="150,*" onload="moveTo(0,0)" border="0">
  <frame name="indice" src="id_prestamo.php" target="principal">
  <frame name="principal" src="logo_prestamo.html" scrolling="auto">
  <noframes>
  <body>
  <p>Esta página usa marcos, pero su explorador no los admite.</p>
  </body>
  </noframes>
</frameset>
<?php
}
else
{
echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=/omp/login_form.php>";
}
?>
</html>
