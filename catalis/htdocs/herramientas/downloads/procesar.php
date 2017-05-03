<?php
$base=$_POST['base'];
$so=$_POST['so'];

// TO-DO: compactar las bases usando mx, antes de comprimirlas con zip

// Si el servidor es windows
if (strtoupper(substr(php_uname(), 0, 3)) === 'WIN'){
	// Paso 1: copiamos las bases a un directorio propio
	shell_exec ("RD /s/q $base&"
	. "mkdir $base\\$so&"
	. "copy c:\\CaMPI\\catalis\bases\\biblio\\$base\\biblio.mst $base\\$so&"
	. "copy c:\\CaMPI\\catalis\bases\\biblio\\$base\\biblio.xrf $base\\$so"
	);
	
	// Paso 2: si es necesario, las convertimos al formato de Linux
	if ($so=="linux") {
		shell_exec("cd $base/$so&"
			. "rename biblio.mst biblioWin.mst&"
			. "rename biblio.xrf biblioWin.xrf&"
			. "crunchmf biblioWin biblio"
		);
	}

	// Paso 3: comprimimos los archivos
	shell_exec("cd $base/$so&"
			  . "C:/CaMPI/bin/cisis/7z a -tzip biblio biblio.*");
	
	// Paso 4: enviamos el archivo .zip al cliente	  
	$archivo = "$base/$so/biblio.zip";
	$today = date('Ymd');
	$TheFileName = $base . '-' . $today . '.zip';
	header( "Content-Type: application/octet-stream");
	header( "Content-Length: " . filesize($archivo));
	header( "Content-Disposition: attachment; filename=" . $TheFileName);
	readfile($archivo);	
	
}

//Si el servidor no es windows
else {
	// Paso 1: copiamos las bases a un directorio propio
	shell_exec("rm $base/$so/*;"
			  . "rmdir $base/$so -p;"
			  . "mkdir $base;mkdir $base/$so;"
			  . "cp /var/www/catalis/bases/catalis_pack_en_produccion/catalis/$base/biblio.mst $base/$so;"
			  . "cp /var/www/catalis/bases/catalis_pack_en_produccion/catalis/$base/biblio.xrf $base/$so"
	);

	// Paso 2: si es necesario, las convertimos al formato de Windows
	if ($so=="windows") {
		shell_exec("cd $base/$so;"
				  . "rename biblio biblioLin biblio.*;"
				  . "/opt/cisis/crunchmf "
				  . "/var/www/catalis/htdocs/herramientas/downloads/$base/$so/biblioLin "
				  . "/var/www/catalis/htdocs/herramientas/downloads/$base/$so/biblio");
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
}
?>
