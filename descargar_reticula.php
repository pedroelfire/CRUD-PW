<?php
session_start(); // Iniciar sesión

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
} else {
    header("Location: login.php");
    exit();
}

$target_dir = "reticulas/";

if (isset($_GET['file'])) {
    $file = urldecode($_GET['file']);
    $file_path = $target_dir . $file;

    if (file_exists($file_path)) {
        // Descargar el archivo
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file_path));
        readfile($file_path);
        exit;
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "Nombre de archivo no proporcionado";
    exit();
}
?>
