<?php
if(!isset($_SESSION['usuario'])) {
    if(!session_id()){
        session_start();
    }

    $servername = "localhost";
    $username = "root";
    $dbPassword = "";
    $dbname = "usuarios";

    // Crear conexión
    $conn = new mysqli($servername, $username, $dbPassword);

    // Verificar conexión
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Crear base de datos si no existe
    $sqlCreateDB = "CREATE DATABASE IF NOT EXISTS $dbname";
    if ($conn->query($sqlCreateDB) === TRUE) {
        echo "Base de datos creada correctamente<br>";
    } else {
        die("Error al crear la base de datos: " . $conn->error);
    }

    // Seleccionar la base de datos
    $conn->select_db($dbname);

    // Crear tabla de usuarios si no existe
    $sqlCreateTable = "CREATE TABLE IF NOT EXISTS usuarios (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        usuario VARCHAR(30) NOT NULL,
        nombre VARCHAR(50) NOT NULL,
        correo VARCHAR(50) NOT NULL,
        contrasena VARCHAR(255) NOT NULL,
        esAdmin BOOLEAN NOT NULL DEFAULT FALSE
    )";
    if ($conn->query($sqlCreateTable) === TRUE) {
        echo "Tabla de usuarios creada correctamente<br>";
    } else {
        die("Error al crear la tabla de usuarios: " . $conn->error);
    }

    // Verificar si el usuario Admin ya existe
    $sqlCheckAdmin = "SELECT usuario FROM usuarios WHERE usuario = 'Admin'";
    $resultAdmin = $conn->query($sqlCheckAdmin);

    // Crear usuario Admin si no existe
    if ($resultAdmin->num_rows == 0) {
        // Crear hash de la contraseña
        $hash = password_hash('123456789', PASSWORD_DEFAULT);

        // Consulta SQL para insertar usuario Admin
        $sqlInsertAdmin = "INSERT INTO usuarios (usuario, nombre, correo, contrasena, esAdmin) "
                . "VALUES ('Admin', 'Admin', 'admin@root', '$hash', TRUE)";

        if ($conn->query($sqlInsertAdmin) === TRUE) {
            echo "Usuario Admin creado correctamente<br>";
        } else {
            die("Error al crear usuario Admin: " . $conn->error);
        }
    }

    // Cerrar conexión
    $conn->close();
}

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    echo "Bienvenid@ $usuario <br>";
    if($_SESSION['esAdmin'] === 1){
        echo "Esta cuenta puede manejar el CRUD";
    }
} else {
    header("Location: login.html");
    exit();
}
?>



<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <title>Bienvenido</title>
</head>
<body>
    
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Acciones</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
            <?php
                if($_SESSION['esAdmin'] && $_SESSION['esAdmin'] == 1){
                   echo '<a class="nav-link active" aria-current="page" href="crud.php">CRUD</a>';
                }
            ?>

        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Generacion de reticula</a>
        </li>
            <li>
                <a class="nav-link active" href="logout.php">Cerrar Sesion</a>
            </li>

          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
</body>
</html>
