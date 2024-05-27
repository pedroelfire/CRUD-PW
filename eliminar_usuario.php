<?php
require_once 'conexion.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

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
