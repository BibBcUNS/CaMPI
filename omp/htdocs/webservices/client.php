<?php
// Crear el cliente del WS
ini_set("soap.wsdl_cache_enabled", "0");
ini_set("display_errors", "1");
?>
<html><head><title>Estado de cuenta</title></head><body>
<h3>Estado de cuenta del usuario - BC</h3>

<?php

if (isset($document)) {
   $clientUrl="http://localhost/omp/webservices/server.php?wsdl";
   $usuario = new SoapClient($clientUrl);
   echo "Estado: <b>".$usuario->getStatus($document)."</b>";
   unset($document); ?>
   <form action="client.php">
   <input type="submit" value="Otro usuario..."/>
   </form>
<?php
}
else {
?>
   <form method="get" action="client.php">
   Documento: <input type="text" name="document"/>
   <input type="submit">
   </form>
<?php
}
?>

</body></html>

