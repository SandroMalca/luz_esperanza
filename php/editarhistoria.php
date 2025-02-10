<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    // Actualizar datos de la historia
    $conexion->query("update historia set 
        fecha='".mysqli_real_escape_string($conexion,$_POST['fecha'])."',
        id_doctor='".(isset($_POST['id_doctor']) ? $_POST['id_doctor'] : "")."',
        motivo='".mysqli_real_escape_string($conexion,$_POST['motivo'])."',
        enfermedad_actual='".mysqli_real_escape_string($conexion,$_POST['enfermedad_actual'])."',
        antec_familiar='".mysqli_real_escape_string($conexion,$_POST['antec_familiar'])."',
        exam_fisico='".mysqli_real_escape_string($conexion,$_POST['exam_fisico'])."',
        diag_presuntivo='".mysqli_real_escape_string($conexion,$_POST['diag_presuntivo'])."',
        exam_auxiliar='".mysqli_real_escape_string($conexion,$_POST['exam_auxiliar'])."',
        laboratorio='".mysqli_real_escape_string($conexion,$_POST['laboratorio'])."',
        otros='".mysqli_real_escape_string($conexion,$_POST['otros'])."',
        diag_definitivo='".mysqli_real_escape_string($conexion,$_POST['diag_definitivo'])."',
        tratamiento='".mysqli_real_escape_string($conexion,$_POST['tratamiento'])."'
        where id=".mysqli_real_escape_string($conexion,$_POST['id']));

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Error al actualizar la historia: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>