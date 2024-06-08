<?php
session_start();
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $numero = $_POST['numero'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $contrasena = $_POST['contrasena'];
    $tipoPerfil = $_POST['tipoPerfil'];
    $esAdmin = isset($_POST['esAdmin']) ? 1 : 0;

    insertarUsuario($usuario,$numero, $nombre, $correo, $contrasena, $esAdmin, $tipoPerfil);
    
    if (isset($_SESSION['registro_exitoso'])) {
        $mensaje_exito = $_SESSION['registro_exitoso'];
        unset($_SESSION['registro_exitoso']);
    } elseif (isset($_SESSION['registro_error'])) {
        $mensaje_error = $_SESSION['registro_error'];
        unset($_SESSION['registro_error']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Añadir Usuario</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper {
            width: 500px;
            margin: 0 auto;
            padding-top: 50px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Añadir Usuario</h2>
        <?php
        if (!empty($mensaje_exito)) {
            echo '<div class="alert alert-success">' . $mensaje_exito . '</div>';
        }
        if (!empty($mensaje_error)) {
            echo '<div class="alert alert-danger">' . $mensaje_error . '</div>';
        }
        ?>
        <form action="agregar_usuario.php" method="post">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>
                <div class="form-group">
                <label>Numero de tarjeta</label>
                <input type="text" name="numero" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Contraseña</label>
                <input type="password" name="contrasena" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Tipo de Perfil</label>
                <select name="tipoPerfil" class="form-control" required>
                    <option value="Maestro">Maestro</option>
                    <option value="Secretario">Secretario</option>
                    <option value="Vocero">Vocero</option>
                </select>
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="esAdmin"> ¿Es Administrador?</label>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Añadir">
                <a href="catalogo_usuarios.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
