<?php
    $conexion = @mysqli_connect(
        'localhost',
        'root',
        'Luis1',
        'catalogovod'
    );

    /**
     * NOTA: si la conexión falló $conexion contendrá false
     **/
    if(!$conexion) {
        die('¡Base de datos NO conextada!');
    }
?>