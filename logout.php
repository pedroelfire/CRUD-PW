<?php
session_start();

$_SESSION['usuario'] = null; // Opcional, dependiendo de cómo manejes la sesión
session_destroy();

header("Location: login.html");
exit();
?>
