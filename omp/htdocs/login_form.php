<?php session_start();?>
<html>
  <head>
    <title>CaMPI - Login</title>
    <link rel="stylesheet" type="text/css" href="/omp/css/style.css" >
<?php
$modulo = $_GET['modulo'];
if (isset($_SESSION["s_username"]) && isset($_SESSION["s_permisos"])	&& in_array($modulo , $_SESSION["s_permisos"])) {
	$permiso = array('circulacion', 'estadisticas', 'administracion');
	switch($modulo) {
		case 'circulacion':
			echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=circulacion/circulacion.php>";
			break;
		case 'estadisticas':
			echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=estadisticas/estadisticas.php>";
			break;
		case 'administracion':
			echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=administracion/menu.php>";
			break;
		default: break;
	}
}
else {
?>	
  </head>
  <body><center>
    <div id="head"> 
		<div id="title">CaMPI > 
<?php switch ($modulo){
	case 'circulacion': echo 'Circulación';break;
	case 'estadisticas': echo 'Estadísticas';break;
	case 'administracion': echo 'Administración';break;
	default:;
}?>	(OpenMarcoPolo) </div>
		<div id="logo"><img src="/omp/images/logocampi.gif" width="120" height="54"></div>
    </div>
    <div id="body_wrapper">
      <div id="body">
		 <div id="all">
			<div class="top"></div>
			<div class="content">
<!--############################################-->  

<br>
<form action='login_valida.php' method='POST'>
<input type=hidden name=modulo value="<?php echo $_GET['modulo'];?>">
<table align='center' width="500" style='border:1px solid #000000;'>
<tr>
	<td align='center' colspan="2">
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
		Nombre de usuario: 
    </td>
    <td>
    	<input type='text' size='15' maxlength='25' name='username'> (Usuario=admin)
	</td>
</tr>
<tr>
	<td align='right'>
		Password: 
    </td>
    <td>    
    	<input type='password' size='15' maxlength='25' name='password'> (password=admin)
	</td>
</tr>
<tr>
	<td align='center' colspan="2">
	<input type="submit" value="Ingreso a Circulación" class="boton">
	</td>
</tr>
</table>
</form>

<!--########################################-->  
</div>
								<div class="bottom"></div>
						</div>
        <div class="clearer"></div>
      </div>
      <div class="clearer"></div>
    </div>
    <div id="end_body"></div>

		<div id="footer"></div></center>
  </body>

<?php
}
?>
</html>