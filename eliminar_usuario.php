<?php
session_start(); // Inicia la sesión al principio del archivo

// Verifica si no hay un usuario en la sesión o si no es admin
if(!isset($_SESSION['usuario']) || ($_SESSION['esAdmin'] != 1)) {
    header("Location: login.html");
    exit();
}

// Resto de tu código aquí
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    require_once 'conexion.php';

    $resultado = eliminarUsuario($id);

    if ($resultado) {
        // Redirigir a la página de usuarios con un mensaje de éxito
        echo "<script>
                alert('Usuario eliminado correctamente');
                window.location.href = 'catalogo_usuarios.php';
              </script>";
    } else {
        echo "Error al eliminar el usuario.";
    }
} else {
    echo "ID de usuario no proporcionado.";
    exit;
}
?>
