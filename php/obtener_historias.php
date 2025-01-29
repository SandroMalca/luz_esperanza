<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$id_cliente = isset($_GET['id']) ? $_GET['id'] : 0;

$historias = array();
$resultado = $conexion->query("SELECT h.*, 
    d.nombre as doctor_nombre, 
    d.ape1 as doctor_ape1, 
    d.ape2 as doctor_ape2 
    FROM historia h 
    LEFT JOIN persona d ON h.id_doctor = d.id 
    WHERE h.id_cliente = " . $id_cliente . " 
    ORDER BY h.id DESC");

while ($historia = mysqli_fetch_assoc($resultado)) {
    $historias[] = array(
        'id' => $historia['id'],
        'id_cliente' => $historia['id_cliente'],
        'id_doctor' => $historia['id_doctor'],
        'fecha' => $historia['fecha'],
        'motivo' => $historia['motivo'],
        'enfermedad_actual' => $historia['enfermedad_actual'],
        'antec_familiar' => $historia['antec_familiar'],
        'antec_personales' => $historia['antec_personales'],
        'exam_fisico' => $historia['exam_fisico'],
        'diag_presuntivo' => $historia['diag_presuntivo'],
        'exam_auxiliar' => $historia['exam_auxiliar'],
        'laboratorio' => $historia['laboratorio'],
        'otros' => $historia['otros'],
        'diag_definitivo' => $historia['diag_definitivo'],
        'tratamiento' => $historia['tratamiento'],
        'doctor_nombre' => $historia['doctor_nombre'],
        'doctor_ape1' => $historia['doctor_ape1'],
        'doctor_ape2' => $historia['doctor_ape2']
    );
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($historias);
?> 