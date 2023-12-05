<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo VOD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body  style="background-color: #d7e3fc;">
    <nav class="navbar navbar-expand-lg navbar-light bg-info">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                Catálogo VOD
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto"></ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Buscar" aria-label="Buscar">
                    <button class="btn btn-success" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="mt-4 mb-4 text-center">Peliculas y Series</h1>
        <?php
        libxml_use_internal_errors(true);
        // Validar el XML con el XSD
        $xml = new DOMDocument();
        $xml->load('catalogovod.xml');

        if (!$xml->schemaValidate('catalogovod.xsd')) {
            echo "<p>Error de validación XML:</p>";
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                echo "<p>", $error->message, "</p>";
            }
            libxml_clear_errors();
            exit;
        }

        $xml->load('catalogovod.xml'); // Recargar el XML para procesar después de la validación

        // Incluir archivo de conexión a la base de datos
        include 'database.php';

        // Procesar y mostrar contenido en HTML5
        $cuentas = $xml->getElementsByTagName('cuenta');
        foreach ($cuentas as $cuenta) {
            $correo = $cuenta->getAttribute('correo');

            // Verificar si el correo ya existe en la tabla 'cuenta'
            $result = $conn->query("SELECT id_cuenta FROM cuenta WHERE correo = '$correo'");

            if ($result->num_rows > 0) {
                // El correo ya existe, no es necesario insertar nuevamente
                $row = $result->fetch_assoc();
                $id_cuenta = $row['id_cuenta'];
            } else {
                // El correo no existe, insertar en la tabla 'cuenta'
                $sql = "INSERT INTO cuenta (correo) VALUES ('$correo')";
                $conn->query($sql);

                // Obtener el id_cuenta correspondiente
                $result = $conn->query("SELECT id_cuenta FROM cuenta WHERE correo = '$correo'");
                $row = $result->fetch_assoc();
                $id_cuenta = $row['id_cuenta'];
                //ID_cuenta: $id_cuenta<br>
            }

            $perfiles = $cuenta->getElementsByTagName('perfil');
            foreach ($perfiles as $perfil) {
                $usuario = $perfil->getAttribute('usuario');
                $idioma = $perfil->getAttribute('idioma');
                echo "<p><b>Correo:</b> $correo<br> 
                <b>Usuario:</b> $usuario <br>
                <b>Idioma:</b> $idioma</p>";
        
                // Verificar si el perfil ya existe en la tabla 'perfiles'
                $resultado = $conn->query("SELECT id_perfil FROM perfiles WHERE usuario = '$usuario' AND idioma = '$idioma'");

                if ($resultado->num_rows > 0) {
                    // El perfil ya existe, obtener el id_perfil
                    $row = $resultado->fetch_assoc();
                    $id_perfil = $row['id_perfil'];
                    //echo "<p>ID_perfil: $id_perfil</p>";
                } else {
                    // El perfil no existe, insertar en la tabla 'perfiles'
                    $sql = "INSERT INTO perfiles (usuario, idioma, id_cuenta) VALUES ('$usuario', '$idioma','$id_cuenta')";
                    $conn->query($sql);
                    // Obtener el id_perfil correspondiente
                    $result = $conn->query("SELECT id_perfil FROM perfiles WHERE usuario = '$usuario' AND idioma = '$idioma'");
                    $row = $result->fetch_assoc();
                    $id_perfil = $row['id_perfil'];
                }
            }
        }

        $contenido = $xml->getElementsByTagName('contenido');
        foreach ($contenido as $cont) {
            $peliculas = $cont->getElementsByTagName('peliculas');
            foreach ($peliculas as $pelicula) {
                $region = $pelicula->getAttribute('region');
        ?>
                <div class="row">
                    <div class="col-md-4">
                        <form class="p-4 rounded" style="background-color: #88d1ca;">
                            <div class="mb-3">
                                <label for="tipo" class="form-label">Tipo</label>
                                <select class="form-select" id="tipo" name="tipo">
                                    <option value="pelicula">Película</option>
                                    <option value="serie">Serie</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="region" class="form-label">Región</label>
                                <input type="text" class="form-control" id="region" name="region">
                            </div>
                            <div class="mb-3">
                                <label for="genero" class="form-label">Género</label>
                                <input type="text" class="form-control" id="genero" name="genero">
                            </div>
                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título</label>
                                <input type="text" class="form-control" id="titulo" name="titulo">
                            </div>
                            <div class="mb-3">
                                <label for="duracion" class="form-label">Duración</label>
                                <input type="text" class="form-control" id="duracion" name="duracion">
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar</button>
                        </form>
                    </div>

                    <div class="col-md-8">
                        <h2 class="mt-4 mb-3">Películas</h2>

                        <div class="table-responsive">
                            <table class="table " style="background-color: #c09891;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Región</th>
                                        <th>Género</th>
                                        <th>Título</th>
                                        <th>Duración</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $generos = $pelicula->getElementsByTagName('genero');
                                    foreach ($generos as $genero) {
                                        $nombreGenero = $genero->getAttribute('nombre');

                                        $titulos = $genero->getElementsByTagName('titulo');
                                        foreach ($titulos as $titulo) {
                                            $duracion = $titulo->getAttribute('duracion');
                                            $nombreTitulo = $titulo->nodeValue;
                                            // Obtener el id_contenido
                                            $resultado = $conn->query("SELECT id_contenido FROM contenido WHERE tipo = 'pelicula' AND region = '$region' AND genero = '$nombreGenero' AND titulo = '$nombreTitulo' AND duracion = '$duracion'");
                                            if ($resultado->num_rows > 0) {
                                                $row = $resultado->fetch_assoc();
                                                $id_contenido = $row['id_contenido'];
                                                }
                                            else{//cuando se inicia por primera vez, para que no genere error
                                                $id_contenido = '';
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $id_contenido; ?></td>
                                                <td><?php echo $region; ?></td>
                                                <td><?php echo $nombreGenero; ?></td>
                                                <td><?php echo $nombreTitulo; ?></td>
                                                <td><?php echo $duracion; ?></td>
                                                <td>
                                                    <!-- Botón de eliminar -->
                                                    <button class="btn btn-danger">Eliminar</button>
                                                </td>
                                            </tr>
                                    <?php
                                        //Asignar un tipo Peliculas = 1
                                        $tipo = "pelicula";
                                        // Verificar si la película ya existe en la tabla 'contenido'
                                        $result = $conn->query("SELECT id_contenido FROM contenido WHERE tipo = 'pelicula' AND region = '$region' AND genero = '$nombreGenero' AND titulo = '$nombreTitulo' AND duracion = '$duracion'");
                                        if ($result->num_rows == 0) {
                                            // La película no existe, insertar en la tabla 'contenido'
                                            $sql = "INSERT INTO contenido(tipo, region, genero, titulo, duracion, id_cuenta) VALUES ('$tipo','$region', '$nombreGenero', '$nombreTitulo', '$duracion', '$id_cuenta')";
                                            $conn->query($sql);
                                        }
                                        }         
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <h2 class="mt-4 mb-3">Series</h2>

                        <div class="table-responsive">
                            <table class="table table-striped" style="background-color: #97a97c;">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Región</th>
                                        <th>Género</th>
                                        <th>Título</th>
                                        <th>Duración</th>
                                        <th> </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $series = $cont->getElementsByTagName('series');
                                    foreach ($series as $serie) {
                                        $generos = $serie->getElementsByTagName('genero');
                                        foreach ($generos as $genero) {
                                            $nombreGenero = $genero->getAttribute('nombre');
                                            $titulos = $genero->getElementsByTagName('titulo');
                                            foreach ($titulos as $titulo) {
                                                $duracion = $titulo->getAttribute('duracion');
                                                $nombreTitulo = $titulo->nodeValue;
                                                // Obtener el id_contenido
                                            $resultado = $conn->query("SELECT id_contenido FROM contenido WHERE tipo = 'serie' AND region = '$region' AND genero = '$nombreGenero' AND titulo = '$nombreTitulo' AND duracion = '$duracion'");
                                            if ($resultado->num_rows > 0) {
                                                $row = $resultado->fetch_assoc();
                                                $id_contenido = $row['id_contenido'];
                                                } 
                                                //cuando se inicia por primera vez, para que no genere error
                                                else{
                                                    $id_contenido = '';
                                                }
                                            ?>
                                                <tr>
                                                    <td><?php echo $id_contenido; ?></td>
                                                    <td><?php echo $region; ?></td>
                                                    <td><?php echo $nombreGenero; ?></td>
                                                    <td><?php echo $nombreTitulo; ?></td>
                                                    <td><?php echo $duracion; ?></td>
                                                    <td>
                                                        <!-- Botón de eliminar -->
                                                        <button class="btn btn-danger">Eliminar</button>
                                                    </td>
                                                </tr>
                                    <?php
                                        //Asignar un tipo Series = 2
                                        $tipo = "serie";
                                        // Verificar si la serie ya existe en la tabla 'contenido'
                                        $result = $conn->query("SELECT id_contenido FROM contenido WHERE tipo = 'serie' AND region = '$region' AND genero = '$nombreGenero' AND titulo = '$nombreTitulo' AND duracion = '$duracion'");
                                        if ($result->num_rows == 0) {
                                            // La película no existe, insertar en la tabla 'contenido'
                                            $sql = "INSERT INTO contenido(tipo, region, genero, titulo, duracion, id_cuenta) VALUES ('$tipo','$region', '$nombreGenero', '$nombreTitulo', '$duracion', '$id_cuenta')";
                                            $conn->query($sql);
                                        }
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
            <?php

            }
        }
        // Cierra la conexión a la base de datos
        $conn->close();
    ?>
                     </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>