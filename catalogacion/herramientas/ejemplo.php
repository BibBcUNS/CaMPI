<?php
define("BCS_BORDER" , 1);
define("BCS_ALIGN_CENTER",4 );
define("BCS_IMAGE_PNG",64);
define("BCD_DEFAULT_STYLE", BCS_BORDER | BCS_ALIGN_CENTER | BCS_IMAGE_PNG);
define("BCS_REVERSE_COLOR", 512);
define("BCD_DEFAULT_FOREGROUND_COLOR", 0x101010);
define("BCD_DEFAULT_BACKGROUND_COLOR", 0xFFFFFF);
// Crear una imagen en blanco y añadir algún texto
$im = imagecreatetruecolor(180, 20);
$dbColor=BCD_DEFAULT_STYLE & BCS_REVERSE_COLOR ? BCD_DEFAULT_FOREGROUND_COLOR : BCD_DEFAULT_BACKGROUND_COLOR;
$color_texto = imagecolorallocate($im,($dbColor & 0xFF0000) >> 16, ($dbColor & 0x001100) >> 8, $dbColor & 0x000011);
//$color_texto = imagecolorallocate($im, 250,1 ,1);
imagestring($im, 1, 5, 5,  "Una Sencilla Cadena De Texto", $color_texto);

// Imprimir la imagen
// Liberar memoria
Header("Content-Type: image/png");
	     ImagePng($im);
imagedestroy($im);

?>
