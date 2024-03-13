<?php    
    if( isset($_POST['database']) && isset($_POST['recordId']) && isset($_POST['fileType'])   ){
        $database = ($_POST['database']);
        $recordId = ($_POST['recordId']);
        $fileType = ($_POST['fileType']);

        $target_dir = '../img/' . $database . '/';
        $target_file = $target_dir . $recordId . "." . $fileType;

        // Lógica de eliminacion de imagen
        if (unlink($target_file)) {
            http_response_code(200);
            $respuesta = json_encode("Imagen eliminada satisfactoriamente.");
        } else {
            http_response_code(500);
            $respuesta = json_encode("Error al eliminar la imagen.");
        }

    }else{
        http_response_code(400);
        $respuesta = ("Error. Faltan parametros.");
    }


    echo json_encode($respuesta);
?>