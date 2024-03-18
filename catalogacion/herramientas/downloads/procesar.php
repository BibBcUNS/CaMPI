<?php
$base=$_POST['base'];
$so=$_POST['so'];
// TO-DO: compactar las bases usando mx, antes de comprimirlas con zip

// Paso 1: copiamos las bases a un directorio propio
shell_exec("rm $base/$so/*;"
		  . "rmdir $base/$so -p;"
		  . "mkdir $base;mkdir $base/$so;"
		  . "cp /var/www/catalogacion/catalis/bases/catalis/$base/biblio.mst $base/$so;"
		  . "cp /var/www/catalogacion/catalis/bases/catalis/$base/biblio.xrf $base/$so"
);

// Paso 2: si es necesario, las convertimos al formato de Windows
if ($so=="windows") {
	shell_exec("cd $base/$so;"
			  . "rename biblio biblioLin biblio.*;"
			  . "/opt/cisis/crunchmf "
			  . "/var/www/catalogacion/catalis/htdocs/herramientas/downloads/$base/$so/biblioLin "
			  . "/var/www/catalogacion/catalis/htdocs/herramientas/downloads/$base/$so/biblio");
}

// Paso 3: comprimimos los archivos
shell_exec("cd $base/$so;"
		  . "zip biblio.zip biblio.*");

// Paso 4: enviamos el archivo .zip al cliente	  
$archivo = "$base/$so/biblio.zip";
#$TheFile = basename($archivo);
$today = date('Ymd');
$TheFileName = $base . '-' . $today . '.zip';
header( "Content-Type: application/octet-stream");
header( "Content-Length: " . filesize($archivo));
header( "Content-Disposition: attachment; filename=" . $TheFileName);
readfile($archivo);

?>
