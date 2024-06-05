<?php
session_start(); // Iniciar sesión

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';

// Verificar si hay un mensaje de éxito en la variable de sesión y mostrarlo
if (isset($_SESSION['mensaje_exito'])) {
    $mensaje_exito = $_SESSION['mensaje_exito'];
    unset($_SESSION['mensaje_exito']); // Limpiar la variable de sesión
}

$actualizacionExitosa = false;

if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);
    $parts = explode('_', $file);

    if (count($parts) < 3) {
        echo "Formato de archivo no válido.";
        exit;
    }
} else {
    echo "Nombre de archivo no proporcionado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "reticulas/";
    $new_part1 = $_POST['part1'];
    $new_part2 = $_POST['part2'];
    $new_part3 = $_POST['part3'];

    // Validar que al menos un campo tenga contenido
    if (empty($new_part1) && empty($new_part2) && empty($new_part3)) {
        echo "Al menos un campo debe tener contenido.";
        exit;
    }

    $new_file_name = $new_part1 . '_' . $new_part2 . '_' . $new_part3 . '_' . $parts[3] . '_' . $parts[4] . '_' . $parts[5];

    $old_file_path = $target_dir . $file;
    $new_file_path = $target_dir . $new_file_name;

    if (rename($old_file_path, $new_file_path)) {
        $actualizacionExitosa = true;

        // Guardar mensaje de éxito en variable de sesión
        $_SESSION['mensaje_exito'] = "Archivo actualizado con éxito.";

        // Construir la nueva URL con los cambios
        $new_file_name_encoded = urlencode($new_file_name);
        $new_url = "editar_reticula.php?file=$new_file_name_encoded";

        // Redirigir al usuario a la nueva URL
        header("Location: $new_url");
        exit();
    } else {
        echo "Error al actualizar el archivo.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Retícula</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 500px;
            margin: 0 auto;
            padding-top: 50px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Editar Retícula</h2>
        <?php if (isset($mensaje_exito)): ?>
            <div class="alert alert-success"><?php echo $mensaje_exito; ?></div>
        <?php endif; ?>
        <form action="editar_reticula.php?file=<?php echo urlencode($file); ?>" method="post">
            <div class="form-group">
                <label>Numero de libro</label>
                <input type="text" name="part1" class="form-control" value="<?php echo htmlspecialchars($parts[0]); ?>" required>
            </div>
            <div class="form-group">
                <label>Numero de pagina</label>
                <input type="text" name="part2" class="form-control" value="<?php echo htmlspecialchars($parts[1]); ?>" required>
            </div>
            <div class="form-group">
                <label>Numero de control</label>
                <input type="text" name="part3" class="form-control" value="<?php echo htmlspecialchars($parts[2]); ?>" required>
            </div>
            <input type="submit" class="btn btn-primary" value="Guardar">
            <a href="ver_reticulas.php" class="btn btn-secondary">Cancelar</a>

        </form>
    </div>
</body>
</html>
