<?php
session_start(); // Iniciar sesión

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: login.php");
    exit();
}


// Verificar si el usuario ha iniciado sesión
require_once 'conexion.php';
$usuario = obtenerUsuarioPorUsuario($_SESSION["usuario"]);
$id =  $usuario['id'];
$tipo =  $usuario['tipoPerfil'];
$esAdmin = $usuario['esAdmin'];

// Directorio de retículas
$target_dir = "reticulas/"; // Cambia esto al directorio correcto

// Verificar si el directorio existe
if (!is_dir($target_dir)) {
    die("El directorio de retículas no existe.");
}

// Obtener los archivos del directorio
$files = array_diff(scandir($target_dir), array('.', '..'));

// Filtrar y ordenar los archivos por nombre
$files = array_filter($files, function ($file) use ($target_dir, $esAdmin, $id, $tipo) {
    if (!is_file($target_dir . $file)) {
        return false;
    }

    if ($esAdmin) {
        return true; // Mostrar todos los archivos si el usuario es admin
    }

    // Obtener el nombre del archivo sin la extensión
    $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);

    // Dividir el nombre del archivo en partes
    $parts = explode('_', $filenameWithoutExtension);
    $totalParts = count($parts);


    // Verificar si hay suficientes partes para buscar los IDs
    if ($totalParts < 3) {
        return false;
    }

    $maestroId = $parts[$totalParts - 3];
    $voceroId = $parts[$totalParts - 2];
    $secretarioId = $parts[$totalParts - 1];


    // Mostrar archivos según el tipo de perfil
    if ($tipo == 'Maestro' && $maestroId == $id) {
        return true;
    } elseif ($tipo == 'Vocero' && $voceroId == $id) {
        return true;
    } elseif ($tipo == 'Secretario' && $secretarioId == $id) {
        return true;
    }

    return false;
});

sort($files);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <title>Ver Retículas</title>
    <style>
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
                            echo '<a class="nav-link active" aria-current="page" href="subida.php">Subida de retículas</a>';
                        }
                        ?>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="ver_reticulas.php">Ver retículas</a>
                    </li>
                    <li>
                        <a class="nav-link active" href="logout.php">Cerrar Sesión</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
if (isset($_SESSION['eliminadaExitosamente'])) {
    echo '<div class="alert alert-success mt-3">Reticula eliminada satisfactoriamente.</div>';
    unset($_SESSION['eliminadaExitosamente']); // Eliminar la variable de sesión
}
?>
    <div class="container mt-5">
    <h2>Lista de Retículas</h2>
    <div>
        <p><strong>Tipo de Perfil:</strong> <?php if($_SESSION['esAdmin']=='1'){echo 'Administrador';}else{ echo htmlspecialchars($tipo);} ?></p>
        <p><strong>ID:</strong> <?php echo htmlspecialchars($id); ?></p>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Numero de libro</th>
                <th>Numero de pagina</th>
                <th>Numero de control</th>
                <th>Maestro</th>
                <th>Vocero</th>
                <th>Secretario</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($files) > 0): ?>
            
                <?php foreach ($files as $file): 
                    $filenameWithoutExtension = pathinfo($file, PATHINFO_FILENAME);
                    $parts = explode('_', $filenameWithoutExtension); ?>
                    <tr>
                        <?php foreach ($parts as $part): ?>
                            <td><?php echo htmlspecialchars($part); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" onclick="toggleDropdown(event)">Acciones</button>
                                <div class="dropdown-menu">
                                    <a href="descargar_reticula.php?file=<?php echo urlencode($file); ?>" class="dropdown-item">Descargar</a>
                                    <?php if ($esAdmin): ?>
                                        <a href="editar_reticula.php?file=<?php echo urlencode($file); ?>" class="dropdown-item">Editar</a>
                                        <a href="eliminar_reticula.php?file=<?php echo urlencode($file); ?>" class="dropdown-item">Eliminar</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>

            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center">No hay retículas asociadas.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

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
    <script>
        // Revisar si existe un mensaje de éxito en la variable de sesión
        <?php if (isset($_SESSION['mensaje_exito'])): ?>
            alert("<?php echo $_SESSION['mensaje_exito']; ?>");
            <?php unset($_SESSION['mensaje_exito']); ?> // Limpiar la variable de sesión
        <?php endif; ?>
    </script>

</body>
</html>
