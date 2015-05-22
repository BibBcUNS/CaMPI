<?php

ini_set("display_errors", "1");
// Incluir librerias de Zend necesarias para trabajar con SOAP
require_once 'Zend/Soap/AutoDiscover.php';
require_once 'Zend/Soap/Server.php';


/**
 * Clase que encapsula los servicios publicados.
 */
class UserBC
{
  /**
   * @param string $document
   * @return string
   * @author FM
  */
  function getStatus($document)
  {
	$serviceUrl="http://localhost/omp/cgi-bin/wxis.exe/omp/webservices/wxis/?IsisScript=consulta-adeuda-libros.xis&documento=$document";
	$ptr_serviceUrl=fopen($serviceUrl,"r");
	$result=fread($ptr_serviceUrl,4096);
	fclose($ptr_serviceUrl);
	return $result;
  }
}

// Si llega el parametro wsdl por GET, devolver WSDL del servicio
if(isset($_GET['wsdl']))
{
  // Zend_Soap_AutoDiscover genera el WSDL a partir de la clase Blog y sus comentarios en PHPDoc !
  $autodiscover = new Zend_Soap_AutoDiscover();
  $autodiscover->setClass('UserBC');
  $autodiscover->handle();
} else
{
  // Atender el llamado a un servicio via SOAP
  $serviceUrl="http://localhost/omp/webservices/server.php?wsdl";
  $soap = new Zend_Soap_Server($serviceUrl);
  $soap->setClass('UserBC');
  $soap->handle();
}
?> 
