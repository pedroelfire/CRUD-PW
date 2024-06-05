<?php
session_start(); // Iniciar sesión

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: login.php");
    exit();
}

require_once 'conexion.php';
$target_dir = "reticulas/";

if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);
    $file_path = $target_dir . $file;

    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            // Guardar mensaje de éxito en variable de sesión
            $_SESSION['eliminadaExitosamente'] = true;
            header("Location: ver_reticulas.php");
        } else {
            echo "Error al eliminar el archivo.";
        }
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "Nombre de archivo no proporcionado";
    exit();
}
?>
