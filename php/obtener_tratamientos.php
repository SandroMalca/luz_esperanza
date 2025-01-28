<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

// Obtener tratamientos
$resultado = $conexion->query("SELECT tratamiento.*, producto.nombre as procedimiento, persona.nombre as doctor 
    FROM tratamiento 
    LEFT JOIN producto ON producto.id=tratamiento.id_producto 
    LEFT JOIN persona ON persona.id=tratamiento.id_doctor 
    ORDER BY tratamiento.fecha DESC");

$tratamientos = array();
while($tratamiento = mysqli_fetch_assoc($resultado)) {
    $tratamientos[] = $tratamiento;
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($tratamientos);
?> 