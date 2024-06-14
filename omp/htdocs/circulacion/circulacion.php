<?php session_start(); 
if (isset($_SESSION["s_username"])
	&& isset($_SESSION["s_permisos"])
	&& in_array('circulacion' , $_SESSION["s_permisos"])) {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>CaMPI - Circulación</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script>
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];  
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
    }   
    return "";
}       
function checkCookie() {
    var ventana_informacion_usuario_abierta = getCookie("ventana_informacion_usuario_abierta");
    if (ventana_informacion_usuario_abierta == "") {
        setCookie("ventana_informacion_usuario_abierta","falso",365);
    }
    return ventana_informacion_usuario_abierta;
}
function abrirCerrarVentana() {
   if (window['pu'] != undefined) {
      if (pu.closed) {
         pu=window.open("result_op.htm","ventana_resultado","width=640,height=480","toolbar=no","location=no","titlebar=no","directories=no","status=no","menubar=no","scrollbars=yes","resizable=yes");
      } else {
         pu.close();
         setCookie("ventana_informacion_usuario_abierta","falso",365);
      }
   } else {
      pu=window.open("result_op.htm","ventana_resultado","width=640,height=480","toolbar=no","location=no","titlebar=no","directories=no","status=no","menubar=no","scrollbars=yes","resizable=yes");
      setCookie("ventana_informacion_usuario_abierta","verdadero",365);
   }
}
if (checkCookie() == "verdadero" ) {
   pu=window.open("result_op.htm","ventana_resultado","width=640,height=480","toolbar=no","location=no","titlebar=no","directories=no","status=no","menubar=no","scrollbars=yes","resizable=yes");}
</script>
</head>

<frameset cols="150,*" border="0">
  <frame name="indice" src="id_prestamo.php" target="principal">
  <frame name="principal" src="logo_prestamo.html" scrolling="auto">
  <noframes>
  <body>
  <p>Esta p?ina usa marcos, pero su explorador no los admite.</p>
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
