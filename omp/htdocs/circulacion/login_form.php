<?php session_start();?>
<html>
  <head>
    <title>CaMPI - Login </title>
    <link rel="stylesheet" type="text/css" href="/omp/css/style.css" >
<?php
if (isset($_SESSION["s_username"]) && $_SESSION["s_permiso"]=='circulacion') {
 echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=circulacion.php>";
}else{
?>	
  </head>
  <body><center>
    <div id="head"> 
		<div id="title">Módulo de Circulación - OPEN MarcoPolo</div>
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