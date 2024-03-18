<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Documento sin t&iacute;tulo</title>

	<script language="Javascript" type="text/javascript">
		<!-- Hide script from older browsers

		function validForm(passForm) {
			if (passForm.usuario.value == "") {
				alert("Debe ingresar el usuario")
				passForm.usuario.focus()
				return false
			}
			if (passForm.pw.value == "") {
				alert("Debe ingresar una contraseña")
				passForm.pw.focus()
				return false
			}
			return true
		}
		
		// End hiding script -->
	</script>
	
	<style>
		body {
		background-color:#C9C7BA;
		}
	</style>


</head>

<FORM NAME="entrada" onsubmit="return validForm(this)" ACTION="unir_registros.php" METHOD="post">

<table align="center" width="270" cellspacing="0" cellpadding="0" border="0"
style="	width: 75%;
		background: brown;	
		border: 1px solid #F0F0F0;
		padding: 6px;
		margin: 6px 0;
		color: #F0F0F0;
		font-size: 16px;
		font:Verdana, Arial, Helvetica, sans-serif">
<tr>
	<td height="20" align="center" class="titulo" colspan="2"><strong>Entrada Usuario</strong></td>
</tr>
<tr>
	<td valign="middle" height="60">
		<table width="100%" cellspacing="5" cellpadding="0" border="0">
		<tr>
			<td align="right" valign="middle" width="50%">Usuario</td>
			<td align="left" valign="middle" width="50%">
				<INPUT type="text" name="usuario" size="5" maxlength="20">
				<script>
				  document.log.usuario.focus();
				</script>
			</td>
		</tr>
		<tr>
			<td align="right" valign="middle">Contrasena</td>
			<td align="left" valign="middle">
				<INPUT type="password" name="pw" size="10" maxlength="20">	
			</td>
		</tr>
		</table>
	</td>
</tr>
<tr>
  <td height="35" align="center">
		<INPUT type="Submit" value="Aceptar" class="boton">
	</td>
</tr>			
</table>
</form>

<body>
</body>
</html>
