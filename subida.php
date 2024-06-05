<?php
session_start(); // Inicia la sesión al principio del archivo

// Verifica si no hay un usuario en la sesión o si no es admin
if (!isset($_SESSION['usuario']) || ($_SESSION['esAdmin'] != 1)) {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWrn9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <style>
        .wrapper {
            width: 800px;
            margin: 0 auto;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            z-index: 1000;
        }

        .show-dropdown .dropdown-menu {
            display: block;
        }

        .dropdown.show-dropdown {
            position: relative;
        }

        .dropdown.show-dropdown .dropdown-menu {
            left: 0;
            top: 100%;
        }
    </style>
    <title>Subida de Archivos</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Acciones</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <?php
                        if ($_SESSION['esAdmin'] && $_SESSION['esAdmin'] == 1) {
                            echo '<a class="nav-link active" aria-current="page" href="catalogo_usuarios.php">CRUD</a>';
                            echo '</li> <li class="nav-item">';
                            echo '<a class="nav-link active" aria-current="page" href="subida.php">Subida de reticulas</a>';
                        }
                        ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="ver_reticulas.php">Ver reticulas</a>
                    </li>
                    <li>
                        <a class="nav-link active" href="logout.php">Cerrar Sesion</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <h1>Subir Archivos</h1>
        <form action="subida.php" method="post" enctype="multipart/form-data">
            <label for="filesToUpload">Selecciona los archivos a subir:</label>
            <input type="file" name="fileToUpload[]" id="fileToUpload" multiple>
            <input type="submit" value="Subir Archivos" name="submit">
        </form>

        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Verifica si hay al menos un archivo subido
            if (empty($_FILES['fileToUpload']['name'][0])) {
                echo "<p>No se ha subido ningún archivo.</p>";
            } else {
                $target_dir = "reticulas/";

                // Crear la carpeta si no existe
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                $successFiles = [];
                $failureFiles = [];

                // Procesar cada archivo
foreach ($_FILES['fileToUpload']['name'] as $key => $name) {
    $target_file = $target_dir . basename($name);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Verificar el formato del nombre
    if (!preg_match('/^\d+_\d+_\d+_\d+_\d+_\d+\.(jpg|jpeg|png)$/i', $name)) {
        $uploadOk = 0;
        $failureFiles[] = $name;
        continue; // Saltar al siguiente archivo
    }

    // Comprueba si el archivo ya existe
    if (file_exists($target_file)) {
        $uploadOk = 0;
        $failureFiles[] = $name;
        continue; // Saltar al siguiente archivo
    }

    // Limita el tamaño del archivo
    if ($_FILES["fileToUpload"]["size"][$key] > 500000) {
        $uploadOk = 0;
        $failureFiles[] = $name;
        continue; // Saltar al siguiente archivo
    }

    // Permite solo ciertas extensiones de archivo
    if ($fileType != "jpg" && $fileType != "jpeg" && $fileType != "png") {
        $uploadOk = 0;
        $failureFiles[] = $name;
        continue; // Saltar al siguiente archivo
    }

    // Verifica si $uploadOk está configurado a 0 por un error
    if ($uploadOk == 0) {
        $failureFiles[] = $name;
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$key], $target_file)) {
            $successFiles[] = $name;
        } else {
            $failureFiles[] = $name;
        }
    }
}

                // Mensaje final dependiendo del número de archivos subidos y no subidos
                if (!empty($successFiles)) {
                    echo "<p>Archivos subidos:</p>";
                    echo "<ul>";
                    foreach ($successFiles as $file) {
                        echo "<li>Archivo actualizado con éxito: $file</li>";
                         }
                    echo "</ul>";
                }
                if (!empty($failureFiles)) {
                    echo "<p>Archivos no subidos:</p>";
                    echo "<ul>";
                    foreach ($failureFiles as $file) {
                        echo "<li>Archivo no subido: $file</li>";
                    }
                    echo "</ul>";
                }
            }
        }
        ?>
    </div>
</body>

<script>
    function toggleDropdown(event) {
        var button = event.target;
        var dropdown = button.closest('.dropdown');
        var allDropdowns = document.querySelectorAll('.dropdown');

        allDropdowns.forEach(function (dd) {
            if (dd !== dropdown) {
                dd.classList.remove('show-dropdown');
            }
        });

        dropdown.classList.toggle('show-dropdown');
        event.stopPropagation();
    }

    document.addEventListener('click', function () {
        document.querySelectorAll('.dropdown').forEach(function (dropdown) {
            dropdown.classList.remove('show-dropdown');
        });
    }, false);

    document.querySelectorAll('.dropdown').forEach(function (dropdown) {
        dropdown.addEventListener('click', function (event) {
            event.stopPropagation();
        }, false);
    });
</script>
</html>
