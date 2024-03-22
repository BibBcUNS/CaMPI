<?php 
    // Inicio sesion si no esta iniciada 
    if (session_status() === PHP_SESSION_NONE) {
      session_start();
    }

    $modulo = $_GET['modulo'];


    // Si no existe la sesión se devuelve a login.php con aviso de error
    if (!isset($_SESSION["s_username"]) && !isset($_SESSION["s_permiso"]))
    {
        echo "<META HTTP-EQUIV=Refresh CONTENT=0;URL=login.php?error=data&modulo=$modulo>";
    }else{
        // Si la sesion existe se genera un form oculto con los datos para ingresar al modulo correspondiente. Al cargar la pagina se realiza el submit
        if($modulo == 'catalis'){
            ?>
            <html>
                <body>
                    <form method="POST" action="/catalis/cgi-bin/wxis">
                        <input type="hidden" name="IsisScript" value="catalis/xis/catalis.xis">
                        <input type="hidden" name="userid" value="<?php echo($_SESSION['s_username'])?>">
                        <input type="hidden" name="pw" value="<?php echo($_SESSION['s_password']) ?>">
                        <input type="hidden" name="tarea" value="INICIAR_SESION">
                        <input type="hidden" name="screen">
                    </form>
                </body>
            </html>
            <?php
        }elseif($modulo == 'catauto'){
            ?>
            <html>
                <body>
                    <form method="POST" action="/catauto/cgi-bin/wxis">
                        <input type="hidden" name="IsisScript" value="catauto/xis/catalis.xis">
                        <input type="hidden" name="userid" value="<?php echo($_SESSION['s_username'])?>">
                        <input type="hidden" name="pw" value="<?php echo($_SESSION['s_password']) ?>">
                        <input type="hidden" name="tarea" value="INICIAR_SESION">
                        <input type="hidden" name="screen">
                    </form>
                </body>
            </html>
            <?php
        }elseif($modulo == 'herramientas'){
            ?>
            <html>
                <body>
                    <form method="POST" action="/herramientas/herramientas.php">
                        <input type="hidden" name="usuario" value="<?php echo($_SESSION['s_username'])?>">
                        <input type="hidden" name="password" value="<?php echo($_SESSION['s_password']) ?>">
                    </form>
                </body>
            </html>
            <?php
        }
        
        ?>
            <script>
                window.onload = function(){
                    document.getElementsByTagName("form")[0].submit();
                }
            </script>
        <?php

    }
    
?>
