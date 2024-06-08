<?php
$usuario = $_POST['usuario']; 
$contrasena = $_POST['contrasena']; 
$confirmar_contrasena = $_POST['confirmar_contrasena']; 
$nombre = $_POST['nombre']; 
$correo = $_POST['correo'];
$tipo_perfil = $_POST['tipo_perfil'];

// Comprobar si se han recibido todos los datos
if ($usuario && $contrasena && $confirmar_contrasena && $nombre && $tipo_perfil) {
    if ($contrasena === $confirmar_contrasena) {
        $esAdmin = 0;
        require_once 'conexion.php';
        insertarUsuario($usuario, $nombre, $correo, $contrasena, $esAdmin, $tipo_perfil);
        if (isset($_SESSION['registro_error'])) {
            echo $_SESSION['registro_error'];
            echo '<br><a href="register.html"><button>Regresar</button></a>';
            return;
        }
        echo "¡Usuario registrado exitosamente!<br><br>";
        // Mostrar botón para volver a la página principal
        echo '<a href="login.html"><button>Por favor inicia sesión para ingresar a la página</button></a>';
        exit(); // Asegurar que el script se detenga después de mostrar el mensaje y el botón
    } else {
        echo "Las contraseñas no coinciden. Por favor, inténtalo de nuevo.";
        echo '<br><a href="registrar.html"><button>Regresar</button></a>';
    }
} else {
    echo "Por favor, complete todos los campos.";
    echo '<br><a href="regstrar.html"><button>Regresar</button></a>';
}
?>
