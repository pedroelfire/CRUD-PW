<?php
// Obtener los datos del formulario
$usuario = $_POST['usuario']; // Rellenar con espacios si es necesario
$contrasena = $_POST['contrasena']; // Rellenar con espacios si es necesario
$confirmar_contrasena = $_POST['confirmar_contrasena']; // Rellenar con espacios si es necesario
$nombre = $_POST['nombre']; // Rellenar con espacios si es necesario
$correo = $_POST['correo']; // Rellenar con espacios si es necesario

echo $usuario;

// Comprobar si se han recibido todos los datos
if ($usuario && $contrasena && $confirmar_contrasena && $nombre) {
    // Comprobar si las contraseñas coinciden
    if ($contrasena === $confirmar_contrasena) {
        // Crear una cadena con los datos del usuario y agregar un salto de línea
        require_once 'conexion.php';
        insertarUsuario($usuario, $nombre, $correo, $contrasena);        
        echo "¡Usuario registrado exitosamente!<br><br>";
        // Mostrar botón para volver a la página principal
        echo '<a href="login.html"><button>Porvafor inicia sesion para ingresar a la pagina</button></a>';
        exit(); // Asegurar que el script se detenga después de mostrar el mensaje y el botón
    } else {
        echo "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
    }
} else {
    echo "Por favor, complete todos los campos.";
}
?>
