<?php
session_start(); // Inicia la sesión al principio del archivo

// Verifica si no hay un usuario en la sesión o si no es admin
if(!isset($_SESSION['usuario']) || ($_SESSION['esAdmin'] != 1)) {
    header("Location: login.html");
    exit();
}

// Resto de tu código aquí

require_once 'conexion.php';

$actualizacionExitosa = false;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $usuario = obtenerUsuarioPorId($id);

    if (!$usuario) {
        echo "Usuario no encontrado";
        exit;
    }
} else {
    echo "ID de usuario no proporcionado";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $tipoPerfil = $_POST['tipoPerfil'];
    $usuarioNombre = $_POST['usuario'];

    $resultado = actualizarUsuario($id, $usuarioNombre, $nombre, $correo, $tipoPerfil);

    if ($resultado) {
        $actualizacionExitosa = true;
    } else {
        echo "Error al actualizar el usuario.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuario</title>
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
        <h2>Editar Usuario</h2>
        <form action="editar_usuario.php?id=<?php echo $usuario['id']; ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $usuario['id']; ?>">
            <div class="form-group">
                <label>Usuario</label>
                <input type="text" name="usuario" class="form-control" value="<?php echo $usuario['usuario']; ?>" required>
            </div>
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control" value="<?php echo $usuario['nombre']; ?>" required>
            </div>
            <div class="form-group">
                <label>Correo</label>
                <input type="email" name="correo" class="form-control" value="<?php echo $usuario['correo']; ?>" required>
            </div>
            <div class="form-group">
                <label>Tipo de Perfil</label>
                <select name="tipoPerfil" class="form-control" required>
                    <option value="Maestro" <?php if ($usuario['tipoPerfil'] == 'Maestro') echo 'selected'; ?>>Maestro</option>
                    <option value="Secretario" <?php if ($usuario['tipoPerfil'] == 'Secretario') echo 'selected'; ?>>Secretario</option>
                    <option value="Vocero" <?php if ($usuario['tipoPerfil'] == 'Vocero') echo 'selected'; ?>>Vocero</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Actualizar">
                <a href="catalogo_usuarios.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
    
    <?php if ($actualizacionExitosa): ?>
        <script>
            alert('Usuario actualizado de manera correcta');
            window.location.href = 'catalogo_usuarios.php';
        </script>
    <?php endif; ?>
</body>
</html>
