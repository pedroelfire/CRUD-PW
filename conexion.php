<?php
session_start();

$servername = "localhost";
$username = "root";
$dbPassword = "";
$dbname = "usuarios";

// Función para insertar usuario en la base de datos
function insertarUsuario($usuario, $nombre, $correo, $contrasena, $tipoPerfil) {
    global $servername, $username, $dbPassword, $dbname;
    unset($_SESSION['registro_error']);
    unset($_SESSION['registro_exitoso']);
    // Crear conexión
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Consulta SQL para verificar si el usuario ya existe
    $sqlCheckUser = "SELECT usuario FROM usuarios WHERE usuario = '$usuario'";
    $resultUser = $conn->query($sqlCheckUser);

    // Consulta SQL para verificar si el correo ya existe
    $sqlCheckEmail = "SELECT correo FROM usuarios WHERE correo = '$correo'";
    $resultEmail = $conn->query($sqlCheckEmail);

    // Verificar si ya existe un usuario o un correo
    if ($resultUser->num_rows > 0) {
        $_SESSION['registro_error'] = "El usuario '$usuario' ya existe";
    } elseif ($resultEmail->num_rows > 0) {
        $_SESSION['registro_error'] = "El correo '$correo' ya está registrado";
    } else {
        // Crear hash de la contraseña
        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        // Consulta SQL para insertar usuario
        $sqlInsert = "INSERT INTO usuarios (usuario, nombre, correo, contrasena, tipoPerfil) "
        . "VALUES ('$usuario', '$nombre', '$correo', '$hash', '$tipoPerfil')";

        if ($conn->query($sqlInsert) === true) {
            $_SESSION['registro_exitoso'] = true; // Marcar registro exitoso en la sesión
        } else {
            $_SESSION['registro_error'] = "Error al registrar usuario: " . $conn->error;
        }
    }

    // Cerrar conexión
    $conn->close();
}

// Función para verificar usuario al iniciar sesión
function verificarUsuario($usuario, $contrasena) {
    global $servername, $username, $dbPassword, $dbname;
    // Crear conexión
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Consulta SQL para buscar usuario por nombre de usuario
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        if (password_verify($contrasena, $row['contrasena'])) {
            $_SESSION['usuario'] = $usuario; // Marcar usuario como conectado en la sesión
            $_SESSION['esAdmin'] = $row['esAdmin'];
            echo "Inicio de sesión exitoso <br>";
            echo "<button onclick=\"window.location.href='index.php';\">Ir a la página de inicio</button>";
        } else {
            echo "Credenciales incorrectas";
        }
    } else {
        echo "Usuario no encontrado";
    }

    // Cerrar conexión
    $conn->close();
}

// Función para obtener todos los usuarios registrados
function obtenerUsuarios() {
    global $servername, $username, $dbPassword, $dbname;
    // Crear conexión
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Consulta SQL para obtener todos los usuarios
    $sql = "SELECT id, usuario, nombre, correo, tipoPerfil FROM usuarios";
    $result = $conn->query($sql);

    $usuarios = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }

    // Cerrar conexión
    $conn->close();

    return $usuarios;
}
?>
