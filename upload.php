<?php
session_start(); // Inicia la sesión al principio del archivo

// Verifica si no hay un usuario en la sesión o si no es admin
if(!isset($_SESSION['usuario']) || ($_SESSION['esAdmin'] != 1)) {
    header("Location: login.html");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $target_dir = "reticulas/";
    
    // Crear la carpeta si no existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Comprueba si el archivo es una imagen o no
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "<script>alert('El archivo no es una imagen.'); window.location.href = 'subida.html';</script>";
        $uploadOk = 0;
    }
    
    // Comprueba si el archivo ya existe
    if (file_exists($target_file)) {
        echo "<script>alert('Lo siento, el archivo ya existe.'); window.location.href = 'subida.html';</script>";
        $uploadOk = 0;
    }
    
    // Limita el tamaño del archivo
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "<script>alert('Lo siento, el archivo es demasiado grande.'); window.location.href = 'subida.html';</script>";
        $uploadOk = 0;
    }
    
    // Permite solo ciertos formatos de archivo
    if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg" && $fileType != "gif") {
        echo "<script>alert('Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.'); window.location.href = 'subida.html';</script>";
        $uploadOk = 0;
    }
    
    // Comprueba si $uploadOk está configurado a 0 por un error
    if ($uploadOk == 0) {
        echo "<script>alert('Lo siento, tu archivo no fue subido.'); window.location.href = 'subida.html';</script>";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "<script>alert('El archivo ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " ha sido subido.'); window.location.href = 'subida.html';</script>";
        } else {
            echo "<script>alert('Lo siento, hubo un error al subir tu archivo.'); window.location.href = 'subida.html';</script>";
        }
    }
}
?>
