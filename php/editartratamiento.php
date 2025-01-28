<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    $conexion->query("update tratamiento set 
        fecha='".$_POST['fecha']."', 
        tratamiento='".$_POST['tratamiento']."', 
        cd='".$_POST['cd']."', 
        prox_cita='".$_POST['prox_cita']."'
        where id=".$_POST['id']); 

    $response['success'] = true;
    $response['message'] = 'Tratamiento actualizado correctamente';
} catch (Exception $e) {
    $response['message'] = 'Error al actualizar el tratamiento: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>