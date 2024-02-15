<?php    
    if( isset($_POST['database']) && isset($_POST['recordId']) && isset($_POST['fileType'])   ){
        $database = ($_POST['database']);
        $recordId = ($_POST['recordId']);
        $fileType = ($_POST['fileType']);

        $target_dir = '../img/' . $database . '/';
        $target_file = $target_dir . $recordId . "." . $fileType;

        // Logica de eliminacion de imagen
        if (unlink($target_file)) {
            $respuesta = json_encode("Imagen eliminada");
        } else {
            $respuesta = json_encode("Fallo al eliminar la imagen");
        }

    }else{
        $respuesta = json_encode("Error. Faltan parametros.");
    }


    echo json_encode($target_file);
?>