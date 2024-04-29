<?php
session_start();

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    echo "Bienvenid@ $usuario";
} else {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Bienvenido</title>
</head>
<body>
    <form method="post" action="logout.php">
        <button type="submit">Cerrar Sesión</button>
    </form>
</body>
</html>
