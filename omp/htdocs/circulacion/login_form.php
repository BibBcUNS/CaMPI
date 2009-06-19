<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <title>CaMPI </title>
    <link rel="stylesheet" type="text/css" href="css/style.css" >
<?php
if (isset($_SESSION["s_username"]) && $_SESSION["s_permiso"]=='circulacion') {
 echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=circulacion.php>";
}else{
?>	
  </head>
  <body><center>
    <div id="head"> 
		<div id="title"><p align="left"><br>Módulo de Circulación - OPEN MarcoPolo</p>
		<div id="logo"><img src="images/logocampicir.gif" width="120" height="54" ></div>
		  </div>
    </div> 
    </div> 
    <div id="body_wrapper">
      <div id="body">
					 <div id="all">
								<div class="top"></div>
								<div class="content">
<!------------------------------------------------------------------------------------------------->  

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
</form>
</td>
</tr>
</table>
<!------------------------------------------------------------------------------------------------->  
</div>
								<div class="bottom"></div>
						</div>
        <div class="clearer"></div>
      </div>
	  
      <div class="clearer"></div>
    </div>
    <div id="end_body"></div>

		<div id="footer">Versión Beta<br>(junio 2009)</div></center>
  </body>

<?php
}
?>
</html>