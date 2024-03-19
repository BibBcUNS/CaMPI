<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>CaMPI Catalogación - Herramientas</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="herramientas.css">

</head>
<body>
	<nav class="navbar mb-4" style="background-color: brown;">
		<div style="margin: auto;">
		    <h1 style="color: #FED;font-family: georgia, 'times new roman', serif ;"> <b>CaMPI Catalogación</b></h1>  
		</div>           
	</nav>

	<div id="divForm">
		<form id="loginForm" name="entrada" action="herramientas.php" method="post" style="margin: 0;">
			<h2 id="titleLogin">Herramientas  | Log In</h2>
			<div id="inputsDiv">
			<div>
				<label class="labelLogin" for="usuario">Usuario: </label><input required id="usuario" type="text" name="usuario" size="9" maxlength="20"> 
			</div>
			<div>
				<label class="labelLogin" for="password">Contraseña: </label><input required type="password" name="pw" size="9" maxlength="20"> 
			</div>
			</div>

			<input type="Submit" value="Iniciar sesión" class="btnSubmitForm">

		</form>
	</div>		

	<script>
		window.onload = function(){
			alert("Debería enviarse el form");
		}
	</script>

</body>
</html>
