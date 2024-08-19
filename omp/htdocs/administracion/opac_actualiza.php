<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>
<body>

<?php
	exec('c:/CaMPI/opacmarc/app/bin/up_date.bat campi');
	echo '<span style="color:green">El proceso de actualización ha finalizado.</span>';
?>
<script>
	parent.enable_button(parent.document.actualizar_opac_form.actualizar_opac,'Actualizar Opac');
</script>
</body>
</html>
