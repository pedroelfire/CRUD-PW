<?php
session_start(); // Iniciar sesión

$usuario = $_POST['usuario']; 
$contrasena = $_POST['contrasena']; 

if ($usuario && $contrasena) {
    require_once 'conexion.php';
    verificarUsuario($usuario, $contrasena);

    // Obtener el ID y el tipo de perfil del usuario
$userData = obtenerUsuarioPorUsuario($usuario);

// Verificar si $userData es válido antes de acceder a sus valores
$_SESSION["err_usuario"] = $userData;
if ($userData) {
    $_SESSION['id'] = $userData['id'];
    $_SESSION['tipoPerfil'] = $userData['tipoPerfil'];
} else {
    // Usuario no encontrado, manejar el error aquí
}

    // Redirigir a ver_reticulas.php
    header("Location: index.php");
    exit(); // Asegura que el script se detenga después de la redirección
} else {
    echo "Por favor, complete todos los campos.";
}

if (isset($_SESSION['login_error'])) {
    echo $_SESSION['login_error'];
    unset($_SESSION['login_error']); // Limpiar el mensaje de error después de mostrarlo
}
?>
