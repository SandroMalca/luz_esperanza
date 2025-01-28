<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$id_cliente = isset($_GET['id']) ? $_GET['id'] : 0;

// Obtener historias clínicas
$resultado = $conexion->query("SELECT historia.*, persona.nombre as doctor 
    FROM historia 
    LEFT JOIN persona ON persona.id=historia.id_doctor 
    WHERE historia.id_cliente='$id_cliente' 
    ORDER BY historia.fecha DESC");

$historias = array();
while($historia = mysqli_fetch_assoc($resultado)) {
    $historias[] = $historia;
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($historias);
?> 