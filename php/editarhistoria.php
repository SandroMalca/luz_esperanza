<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    // Actualizar datos de la historia
    $conexion->query("update historia set 
        fecha='".$_POST['fecha']."',
        id_doctor='".$_POST['id_doctor']."',
        motivo='".$_POST['motivo']."',
        enfermedad_actual='".$_POST['enfermedad_actual']."',
        antec_familiar='".$_POST['antec_familiar']."',
        exam_fisico='".$_POST['exam_fisico']."',
        diag_presuntivo='".$_POST['diag_presuntivo']."',
        exam_auxiliar='".$_POST['exam_auxiliar']."',
        laboratorio='".$_POST['laboratorio']."',
        otros='".$_POST['otros']."',
        diag_definitivo='".$_POST['diag_definitivo']."',
        tratamiento='".$_POST['tratamiento']."'
        where id=".$_POST['id']);

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Error al actualizar la historia: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>