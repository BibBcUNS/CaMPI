 <?php 
 $email=$_GET['email']; 
 $message=$_POST['mess'];
 $cabeceras = 'From: sistemasbibliotecas@uns.edu.ar' . "\r\n" .
    'Reply-To: bc@uns.edu.ar' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

mail($para, $titulo, $mensaje, $cabeceras);
 if(!$message){
 ?>
 <html>
 <head></head>
 <body>
 Redactar mensaje:<br />
 <form  method="post" action="<?php echo 'http://'.$_SERVER['SERVER_NAME'].'/omp/circulacion/mensaje.php?email='.$email; ?> " >
 <textarea cols="40" rows="10" wrap="hard" name="mess" ></textarea>
 <!--input type="textarea"  rows="10" cols="20" wrap="hard" /-->
 <br />
 <input type="submit" value="Enviar Mensaje" />
 </form>
 </body>
 </html>

  <?php 
  } else {
  mail($email, "Mensaje de la Biblioteca", $message,$cabeceras); 
?>
 <html>
 <head></head>
 <body>Mensaje enviado existosamente.
 <br />
 <br />
<a href="javascript:close()">Cerrar ventana</a>
 </body>
 </html>
<?php } ?>
