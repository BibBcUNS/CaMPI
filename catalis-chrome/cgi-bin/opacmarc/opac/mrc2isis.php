<?php
// ============================================================================
function mrc2isis($marcRecord,$isisDb)
// input: un registro MARC ISO-2709
// output: el mismo registro, aceptable para isis (ignorando campos locales)
// 
// Generamos un único archivo .txt con el total de los registros, y luego 
// usamos id2i para crear la base 
// ============================================================================
{	
	$SUBFIELD_DELIMITER = "/\x1F/";

	global $text_file;
	
	$leader = substr($marcRecord,0,24);
	$leader = str_replace(" ","#",$leader);

	$baseAddress = substr($leader,12,5);
	$pos = 12;
	
	// Loop sobre los elementos del directorio
	while ($pos+12 < $baseAddress-1) {
		$pos += 12;
		$directoryEntry = substr($marcRecord,$pos,12);
		$tag = substr($directoryEntry,0,3);
		// Ignoramos los campos locales de LC (9xx, 59x, 012, 09x, 850, 890)
		if ( substr($tag,0,1) == '9' || substr($tag,0,2) == '59' || $tag == '012') continue;
		$fieldLength = substr($directoryEntry,3,4);
		$startPos = substr($directoryEntry,7,5);
		$fieldContent = substr($marcRecord, $baseAddress + $startPos, $fieldLength-1);
			if ("008" == $tag || "010" == $tag || "006" == $tag || "007" == $tag) {
			$fieldContent = str_replace(' ','#',$fieldContent);
		}
		$fieldContent = preg_replace($SUBFIELD_DELIMITER,'^',$fieldContent);
		
		// Indicadores: " " --> "#"
		if (substr($tag,0,2) != "00") {
		  $fieldContent = str_replace(' ','#',substr($fieldContent,0,2)) . substr($fieldContent,2);
		}
		
		$textLine = "!v" . $tag . "!" . $fieldContent;
		fputs ($text_file,$textLine."\r\n");  
	}

	// Datos del leader (no usamos tags > 1000 porque se pierden al pasar a un .iso)
	$textLine  = "!v905!" . substr($leader,5,1) . "\r\n";
	$textLine .= "!v906!" . substr($leader,6,1) . "\r\n";
	$textLine .= "!v907!" . substr($leader,7,1) . "\r\n";
	$textLine .= "!v908!" . substr($leader,8,1) . "\r\n";
	$textLine .= "!v909!" . substr($leader,9,1) . "\r\n";
	$textLine .= "!v917!" . substr($leader,17,1) . "\r\n";
	$textLine .= "!v918!" . substr($leader,18,1) . "\r\n";
	$textLine .= "!v919!" . substr($leader,19,1) . "\r\n";
	fputs ($text_file,$textLine."\r\n");
}

// ====================================================================
//  MAIN SECTION
// ====================================================================

if ($argc < 3 || in_array($argv[1], array('--help', '-help', '-h', '-?')))
{
	echo "--------------------------------------\n";
	echo strtoupper($argv[0])."\n";
	echo "--------------------------------------\n";
	echo "Uso:\n";
	echo "    m2i <marc_file> <isis_db> [<max_records>]\n\n";
	echo "Tip: para procesar un lote de archivos *.mrc, ejecute primero\n";
	echo "     copy /b *.mrc allmrc\n\n";
}
else
{
	set_time_limit(0);  // maximum execution time = unlimited 
	$mrc_file_name = $argv[1];
	$isisDb = $argv[2];
	$maxCount = $argv[3];
	if ($maxCount == 0) $maxCount=1000000;

	// Archivo de texto para la salida
	$text_file_name = "marcfile.id";
	unlink($text_file_name);  // borramos archivo
	$text_file = fopen($text_file_name, "w");

	$mrc_file = fopen($mrc_file_name, "rb"); // b - binary safe
	echo "Procesando: " . $mrc_file_name . "\r\n";

	while ( !feof($mrc_file) and $count < $maxCount ) {
		$recSize = fread($mrc_file,5);
		if ($recSize == 0) continue;     /* Para evitar problemas al final */
		$count += 1; 
		if ($count % 10 == 0) {
			echo "# " . $count . "\t" . $recSize . "\n";
		}
		$marcRecord = $recSize . fread($mrc_file,$recSize-5);
		fputs ($text_file, "!ID " . $count . "\r\n");
		// Procesamos el registro
		mrc2isis($marcRecord,$isisDb);
	}
	fclose($mrc_file);
	fclose($text_file);

	echo "Total records: " . $count . "\n";
}
?>
