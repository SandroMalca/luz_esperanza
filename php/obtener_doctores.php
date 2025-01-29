<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$doctores = array();
$resultado = $conexion->query("select id, nombre, ape1, ape2, cmp, especialidad from persona where id_tipopersona='1' ORDER BY ape1, ape2, nombre ASC");

while ($doctor = mysqli_fetch_assoc($resultado)) {
    $doctores[] = array(
        'id' => $doctor['id'],
        'nombre' => $doctor['nombre'],
        'ape1' => $doctor['ape1'],
        'ape2' => $doctor['ape2'],
        'cmp' => $doctor['cmp'],
        'especialidad' => $doctor['especialidad']
    );
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($doctores);
?> 