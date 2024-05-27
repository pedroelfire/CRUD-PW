<?php
// Iniciar sesión solo si no hay una sesión activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$servername = "localhost";
$username = "root";
$dbPassword = "";
$dbname = "usuarios";

// Funciones para la base de datos

function obtenerUsuarios() {
    global $servername, $username, $dbPassword, $dbname;
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);
    $sql = "SELECT * FROM usuarios";
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

function insertarUsuario($usuario, $nombre, $correo, $contrasena, $esAdmin, $tipoPerfil) {
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
        $sqlInsert = "INSERT INTO usuarios (usuario, nombre, correo, contrasena, esAdmin, tipoPerfil) VALUES ('$usuario', '$nombre', '$correo', '$hash', '$esAdmin', '$tipoPerfil')";

        if ($conn->query($sqlInsert) === TRUE) {
            $_SESSION['registro_exitoso'] = "Usuario añadido correctamente";
        } else {
            $_SESSION['registro_error'] = "Error al registrar usuario: " . $conn->error;
        }
    }

    // Cerrar conexión
    $conn->close();
}

function obtenerUsuarioPorId($id) {
    global $servername, $username, $dbPassword, $dbname;
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $result = $conn->query($sql);
    $conn->close();
    return $result->fetch_assoc();
}

function actualizarUsuario($id, $usuario, $nombre, $correo, $tipoPerfil) {
    global $servername, $username, $dbPassword, $dbname;
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "UPDATE usuarios SET usuario=?, nombre=?, correo=?, tipoPerfil=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $usuario, $nombre, $correo, $tipoPerfil, $id);
    
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        return true;
    } else {
        $stmt->close();
        $conn->close();
        return false;
    }
}

function eliminarUsuario($id) {
    global $servername, $username, $dbPassword, $dbname;
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "DELETE FROM usuarios WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $conn->close();
        return true;
    } else {
        $conn->close();
        return false;
    }
}

function verificarUsuario($usuario, $contrasena) {
    global $servername, $username, $dbPassword, $dbname;
    unset($_SESSION['login_error']);
    unset($_SESSION['login_exitoso']);

    // Crear conexión
    $conn = new mysqli($servername, $username, $dbPassword, $dbname);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Consulta SQL para obtener el usuario
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $result = $conn->query($sql);

    // Verificar si el usuario existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verificar la contraseña
        if (password_verify($contrasena, $row['contrasena'])) {
            // Iniciar sesión
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['login_exitoso'] = "Inicio de sesión exitoso";

            // Verificar si es admin
            if ($row['esAdmin'] == 1) {
                $_SESSION['esAdmin'] = 1;
            } else {
                $_SESSION['esAdmin'] = 0;
            }

            // Redirigir a index.php
            header("Location: index.php");
            exit(); // Asegura que el script se detenga después de la redirección
        } else {
            $_SESSION['login_error'] = "Contraseña incorrecta";
        }
    } else {
        $_SESSION['login_error'] = "Usuario no encontrado";
    }

    // Cerrar conexión
    $conn->close();
}


