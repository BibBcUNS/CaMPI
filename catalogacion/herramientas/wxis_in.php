<?php
$verificar = file_get_contents("http://127.0.0.1/cgi-car/wxis.exe?IsisScript=catalis/verificarpw.xis&usuario=$usuario&pw=$pw");

  global $query;
  
  while (list ($key, $val) = each 
  (${"HTTP_".$REQUEST_METHOD."_VARS"})) {
    $query.= "$key=".urlencode($val)."&";
  }

  //readfile("http://$SERVER_NAME:$SERVER_PORT/cgi-bin/wxis.exe?$query");
readfile("http://localhost:$SERVER_PORT/cgi-car/wxis.exe?$query");

?>