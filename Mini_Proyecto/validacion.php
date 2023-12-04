<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo VOD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
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
                <h2 class="mt-4 mb-3">Películas (<?php echo $region; ?>)</h2>

                <div class="table-responsive">
                    <table class="table table-primary">
                        <thead>
                            <tr>
                                <th>Género</th>
                                <th>Título</th>
                                <th>Duración</th>
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
                                        <td><?php echo $nombreGenero; ?></td>
                                        <td><?php echo $nombreTitulo; ?></td>
                                        <td><?php echo $duracion; ?></td>
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
                    <table class="table table-striped table-info">
                        <thead>
                            <tr>
                                <th>Género</th>
                                <th>Título</th>
                                <th>Duración</th>
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
                                            <td><?php echo $nombreGenero; ?></td>
                                            <td><?php echo $nombreTitulo; ?></td>
                                            <td><?php echo $duracion; ?></td>
                                        </tr>
                            <?php
                                    }
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

        <?php
            }
        }
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>