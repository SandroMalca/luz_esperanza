<?php
include "./conexion.php";
date_default_timezone_set('America/Lima');
session_start();

$eventos = array();
$usuario = $_SESSION['user'];
$acceso = $_SESSION['acceso'];

// Construir la consulta base
$query = "SELECT e.*, 
    CONCAT(p.ape1, ' ', COALESCE(p.ape2, ''), ', ', p.nombre) as nombre_paciente,
    CONCAT(d.ape1, ' ', COALESCE(d.ape2, ''), ', ', d.nombre) as nombre_doctor,
    d.especialidad as especialidad_doctor
    FROM eventos e
    LEFT JOIN persona p ON e.id_paciente = p.id
    LEFT JOIN persona d ON e.id_doctor = d.id";

// Si no es superadmin, solo mostrar sus eventos
if ($acceso != 'Superadmin') {
    $query .= " WHERE e.usuario = '$usuario'";
}

$query .= " ORDER BY e.fecha_inicio ASC";

$resultado = $conexion->query($query);

while ($evento = mysqli_fetch_assoc($resultado)) {
    $eventos[] = array(
        'id' => $evento['id'],
        'title' => $evento['titulo'],
        'start' => $evento['fecha_inicio'],
        'end' => $evento['fecha_fin'],
        'backgroundColor' => '#3788d8',
        'borderColor' => '#3788d8',
        'extendedProps' => array(
            'id_paciente' => $evento['id_paciente'],
            'id_doctor' => $evento['id_doctor'],
            'paciente' => $evento['nombre_paciente'],
            'doctor' => $evento['nombre_doctor'],
            'especialidad' => $evento['especialidad_doctor'],
            'horario' => $evento['horario'],
            'fecha' => $evento['fecha']
        )
    );
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($eventos);
?> 