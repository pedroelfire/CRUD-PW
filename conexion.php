<?php
session_start();

$servername = "localhost";
$username = "root";
$dbPassword = "";
$dbname = "usuarios";

// Función para insertar usuario en la base de datos
function insertarUsuario($usuario, $nombre, $correo, $contrasena) {
    global $servername, $username, $dbPassword, $dbname;
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
        $sqlInsert = "INSERT INTO usuarios (usuario, nombre, correo, contrasena) "
                . "VALUES ('$usuario', '$nombre', '$correo', '$hash')";

        if ($conn->query($sqlInsert) === TRUE) {
            $_SESSION['registro_exitoso'] = true; // Marcar registro exitoso en la sesión
        } else {
            $_SESSION['registro_exitoso'] = false; // Marcar registro fallido en la sesión
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