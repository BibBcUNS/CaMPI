<?php
    // Inicio sesion si no esta iniciada 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
?>
<html>
<head>
    <title>Catalogación - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="../css/login.css">

    <?php
        $modulo = $_GET['modulo'];

        if(isset($_SESSION["s_username"]) && isset($_SESSION["s_permiso"])){
            switch($modulo) {
				case 'catalis':
					echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=openModule.php?modulo=$modulo>";
					break;
				case 'catauto':
					echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=openModule.php?modulo=$modulo>";
					break;
				case 'herramientas':
					echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=openModule.php?modulo=$modulo>";
					break;
				default: break;
			}
        }else{ 
            // Presentamos formulario de login ---------------- 
	
    ?>        
    
    
</head>
<body>
    <nav class="navbar mb-4" style="background-color: brown;">
		<div style="margin: auto;">
		    <h1 style="color: #FED;font-family: georgia, 'times new roman', serif ;"> <b>CaMPI Catalogación</b></h1>  
		</div>           
	</nav>
    <br>

    <div id="divForm">
		<form id="loginForm" name="entrada" action="login_valida.php" method="POST">
			<!-- Decidimos el titulo dependiendo del modulo seleccionado -->
			<h2 id="titleLogin"><?php if($modulo != "herramientas"){echo(str_replace("c", "C", $modulo) . " | Log In");}else{echo(str_replace("h", "H", $modulo) . " | Log In");}  ?></h2>
			<div id="inputsDiv">
			    <div>
			    	<label class="labelLogin" for="usuario">Usuario: </label><input required id="username" type="text" name="username" size="9" maxlength="20"> 
			    </div>
			    <div>
			    	<label class="labelLogin" for="password">Contraseña: </label><input required type="password" name="password" size="9" maxlength="20"> 
			    </div>
			</div>

            <input type="hidden" name="modulo" value=<?php echo("$modulo") ?>>

			<input type="Submit" value="Iniciar sesión" class="btnSubmitForm">

		</form>
	</div>		




</body>
<?php
    }
?>
</html>