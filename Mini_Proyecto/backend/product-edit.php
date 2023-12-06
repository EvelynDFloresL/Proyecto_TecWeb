<?php
    include_once __DIR__.'/database.php';

    // SE OBTIENE LA INFORMACIÓN DEL PRODUCTO ENVIADA POR EL CLIENTE
    $titulo = file_get_contents('php://input');
    $data = array(
        'status'  => 'Error',
        'message' => 'No fue posible actualizar'
    );
    if(isset($titulo)) {
        // SE TRANSFORMA EL STRING DEL JASON A OBJETO
        $jsonOBJ = json_decode($titulo);
        $id = $jsonOBJ->id;
        // SE ASUME QUE LOS DATOS YA FUERON VALIDADOS ANTES DE ENVIARSE
        $sql = "SELECT * FROM contenido WHERE id_contenido = {$id}";
	    $result = $conexion->query($sql);
        
        if ($result->num_rows != 0) {
            $conexion->set_charset("utf8");
            $sql = "UPDATE contenido  SET titulo = '{$jsonOBJ->titulo}', genero = '{$jsonOBJ->genero}', tipo = '{$jsonOBJ->tipo}', region = '{$jsonOBJ->region}', duracion = '{$jsonOBJ->duracion}' WHERE id_contenido = {$id}";
            if($conexion->query($sql)){
                $data['status'] =  "Success";
                $data['message'] =  "Titulo actualizado";
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