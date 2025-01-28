<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

// Obtener todos los usuarios
$resultado = $conexion->query("SELECT * FROM logueo ORDER BY user ASC");
$usuarios = array();

while($user = mysqli_fetch_assoc($resultado)) {
    $usuarios[] = $user;
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($usuarios);
?> 