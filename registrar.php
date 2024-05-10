<?php
$usuario = $_POST['usuario']; 
$contrasena = $_POST['contrasena']; 
$confirmar_contrasena = $_POST['confirmar_contrasena']; 
$nombre = $_POST['nombre']; 
$correo = $_POST['correo']; 

// Comprobar si se han recibido todos los datos
if ($usuario && $contrasena && $confirmar_contrasena && $nombre) {
    // Comprobar si las contraseñas coinciden
    if ($contrasena === $confirmar_contrasena) {
        // Crear una cadena con los datos del usuario y agregar un salto de línea
        $esAdmin = 0;
        require_once 'conexion.php';
        insertarUsuario($usuario, $nombre, $correo, $contrasena, $esAdmin);
            if(isset($_SESSION['registro_error'])) {
            echo $_SESSION['registro_error'];
            return;
            }
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
