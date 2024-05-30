<?php
session_start(); // Iniciar sesión

// Verificar si el usuario ha iniciado sesión
$id = $_SESSION['id'];
echo $_SESSION['usuario'];
echo $_SESSION["err_usuario"],"aa";
$tipoPerfil = $_SESSION['tipoPerfil'];

// Directorio de retículas
$target_dir = "reticulas/"; // Cambia esto al directorio correcto

// Verificar si el directorio existe
if (!is_dir($target_dir)) {
    die("El directorio de retículas no existe.");
}

// Obtener los archivos del directorio
$files = array_diff(scandir($target_dir), array('.', '..'));

// Filtrar y ordenar los archivos por nombre
$files = array_filter($files, function ($file) use ($target_dir) {
    return is_file($target_dir . $file);
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
</head>
<body>
    <div class="container mt-5">
        <h2>Lista de Retículas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre del Archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $file): ?>
                <tr>
                    <td><?php echo htmlspecialchars($file); ?></td>
                    <td>
                        <a href="<?php echo $target_dir . htmlspecialchars($file); ?>" download class="btn btn-primary">Descargar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
