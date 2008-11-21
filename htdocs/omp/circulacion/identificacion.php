<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <title>identificación </title>
    
<style type="text/css">
<!--
#body {
   background : url(../images/body_ident.jpg) no-repeat ;
}
#lt {
   float : left;
   width : 203px;
   margin :0 0 0 0.45em;
   border : 1px solid #5277AE;
   padding : 0;
   background : #BEE4FF;
   font-family: "Trebuchet MS", Verdana, sans-serif;
}

#lt p {
   margin : 1.2em 0.75em 1.2em 0.75em;
   font-size : 13px;
}
#lt h2 {
	margin : 0;
	color: #fff;
	text-align: center;
	font-size : 80%;
	background: #5277AE;
}
-->
</style>
  </head>
 <body>
 <div id="body">
   <div id="lt"><br>
<!--------------------------------------------------------->   
<?php
$usuario=$_SESSION["s_username"];
$url="http://$SERVER_NAME/cgi-bin/wxis.exe/omp/circulacion/?IsisScript=omp/circulacion/identificacion_id.xis&id_operador=".$usuario;
$ptr_grabar_datos = fopen($url,"r");
$grabar_datos = fread($ptr_grabar_datos,500);
fclose($ptr_grabar_datos);
echo '<b><p align="center">OPERADOR<br>'.$grabar_datos.'</b></p>';
?>
<!-------------------------------------------------------------------------->
    </div>
 </div>		
  </body>
</html>
