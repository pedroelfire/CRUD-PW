<?php
// Obtener los datos del formulario
$usuario = $_POST['usuario']; // Rellenar con espacios si es necesario
$contrasena = $_POST['contrasena']; // Rellenar con espacios si es necesario

// Comprobar si se han recibido todos los datos
if ($usuario && $contrasena) {
    require_once 'conexion.php';
    verificarUsuario($usuario, $contrasena);
} else {
    echo "Por favor, complete todos los campos.";
}