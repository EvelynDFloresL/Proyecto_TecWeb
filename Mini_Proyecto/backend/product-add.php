<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $title = file_get_contents('php://input');
    $data = array(
        'status'  => 'Error',
        'message' => 'Ya existe un titulo con ese nombre'
    );
    if(!empty($title)) {
        // SE TRANSFORMA EL STRING DEL JASON A OBJETO
        $jsonOBJ = json_decode($title);
        // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
        $sql = "SELECT * FROM contenido WHERE titulo = '{$jsonOBJ->titulo}' AND eliminado = 0";
	    $result = $conexion->query($sql);
        
        if ($result->num_rows == 0) {
            $conexion->set_charset("utf8");
            $sql = "INSERT INTO contenido VALUES (null, '{$jsonOBJ->tipo}', '{$jsonOBJ->region}', '{$jsonOBJ->genero}', '{$jsonOBJ->titulo}', '{$jsonOBJ->duracion}', 0, 1)";
            if($conexion->query($sql)){
                $data['status'] =  "Success";
                $data['message'] =  "Titulo agregado";
            } else {
                $data['message'] = "ERROR: No se ejecuto $sql. " . mysqli_error($conexion);
            }
        }

        $result->free();
        // Cierra la conexion
        $conexion->close();
    }

    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>