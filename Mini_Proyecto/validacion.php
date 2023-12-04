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

        // Procesar y mostrar contenido en HTML5
        $cuentas = $xml->getElementsByTagName('cuenta');
        foreach ($cuentas as $cuenta) {
            $correo = $cuenta->getAttribute('correo');
            echo "<p>Correo: $correo</p>";

            $perfiles = $cuenta->getElementsByTagName('perfil');
            foreach ($perfiles as $perfil) {
                $usuario = $perfil->getAttribute('usuario');
                $idioma = $perfil->getAttribute('idioma');
                echo "<p>Usuario: $usuario <br>
                Idioma: $idioma</p>";
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
                                    ?>
                                            <tr>
                                                <td>Poner id aqui</td>
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
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                        <h2 class="mt-4 mb-3">Series (<?php echo $region; ?>)</h2>

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
                                    ?>
                                                <tr>
                                                    <td>Poner id aqui</td>
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
            ?>
                </div>
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>