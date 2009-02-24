<?php session_start();?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>CaMPI </title>
    <link rel="stylesheet" type="text/css" href="/css/style.css" />
<?php
if (isset($_SESSION["s_username"]) && $_SESSION["s_permiso"]=='circulacion') {
 echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=circulacion.php>";
}else{
?>	
  </head>
  <body>
    <div id="head">
		  <div id="title">Módulo de Circulación   
		  <div id="logo"><img src="/images/logocampi2.gif"   width="156" height="71" ></div>
		  </div>
		 
      <div id="menu">
        <ul>
          <li>
            <a href="/index.html"  target=_self>Principal</a>
          </li>
          <li class="active">
            <a href="*" >Circulación</a>
          </li>
          <li>
            <a href="/omp/administracion/login_form.php"  target=_self>Administración</a>
          </li>
          <li>
            <a href="/omp/estadisticas/index.htm"  target=_self>Estadísticas</a>
          </li>
		  <li>
             <a href="/catalis/catalogacion.htm" target=_self>
			 Catalogación</a>
          </li>
		  <li>
            <a href= "/cgi-bin/opacmarc/wxis.exe?IsisScript=opac/xis/opac.xis&db=demo&showForm=simple" 
			target=_self>OPAC</a>
          </li>
        </ul>
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
        <div class="clearer">
</div>
      </div>
      <div class="clearer">
	  </div>
    </div>
    <div id="end_body"></div>

		<div id="footer">Versión 0.1<br>(Julio 2008)</div>
  </body>

<?php
}
?>
</html>