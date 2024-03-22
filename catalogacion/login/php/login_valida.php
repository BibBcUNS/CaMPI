<?php
    // Consulta a un archivo .xis (el cual consulta a base de datos USERS) para validar la existencia de usuario
    // Este archivo .xis se encuentra dentro de la estructura de carpeta de Catalis (ya que es indistinto entre Catalis y Catauto)
    

    // Inicio sesion si no esta iniciada 
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $username = $_POST['username'];
    $password = $_POST['password'];
    $modulo = $_POST['modulo'];
    

    if ($password==NULL or $username==NULL) {
        echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.php?error=data&modulo=$modulo>";
    }else{

        // TO-DO En esta seccion deberiamos encriptar la contraseña para buscarla con el archivo .xis...
        // El camino correcto seria obtener la contraseña de la base de datos y validarla con password_validate contra la contraseña que viene por post. Si se valida entonces se da acceso

        $cadena_archivo = "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/catalis/cgi-bin/wxis?IsisScript=../../login/xis/userExist.xis&user=".$username."&pwd=".$password;
        
        $permiso = file_get_contents($cadena_archivo);
        

        if($permiso=="false"){
            echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.php?error=data&modulo=$modulo>";
        }else{
            $_SESSION["s_username"] = $username;
            $_SESSION["s_password"] = $password;
            $_SESSION["s_permiso"] = $permiso;
            switch($modulo) {
                case 'catalis':
					echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=openModule.php?modulo=catalis>"; 
					break;
				case 'catauto':
					echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=openModule.php?modulo=catauto>";
					break;
				case 'herramientas':
					echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=openModule.php?modulo=herramientas>";
					break;
				default: break;
            }
        }
    }
?> 
     