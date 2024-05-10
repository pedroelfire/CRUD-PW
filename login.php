<?php
$usuario = $_POST['usuario']; 
$contrasena = $_POST['contrasena']; 

if ($usuario && $contrasena) {
    require_once 'conexion.php';
    verificarUsuario($usuario, $contrasena);
} else {
    echo "Por favor, complete todos los campos.";
}