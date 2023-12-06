<?php
    include_once __DIR__.'/database.php';

    // SE CREA EL ARREGLO QUE SE VA A DEVOLVER EN FORMA DE JSON
    $data = array();
    // SE VERIFICA HABER RECIBIDO EL ID
    if( isset($_GET['titulo']) ) {
        $search = $_GET['titulo'];
        // SE REALIZA LA QUERY DE BÚSQUEDA Y AL MISMO TIEMPO SE VALIDA SI HUBO RESULTADOS
        $sql = "SELECT * FROM contenido WHERE titulo = '{$search}' AND eliminado = 0";
        if ( $result = $conexion->query($sql) ) {

            if($result->num_rows != 0) {
                // SE CODIFICAN A UTF-8 LOS DATOS Y SE MAPEAN AL ARREGLO DE RESPUESTA
                $data = array(
                    'status'  => 'Error',
                    'message' => 'Ya existe un titulo con ese nombre'
                );
            } else {
                $data = array(
                    'status'  => 'Success',
                    'message' => 'Nombre disponible'
                );
            }

			$result->free();
		} else {
            die('Query Error: '.mysqli_error($conexion));
        }
		$conexion->close();
    } 
    
    // SE HACE LA CONVERSIÓN DE ARRAY A JSON
    echo json_encode($data, JSON_PRETTY_PRINT);
?>
