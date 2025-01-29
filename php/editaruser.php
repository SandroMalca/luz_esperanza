<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    $conexion->query("update logueo set 
        user='".$_POST['user']."', 
        acceso='".$_POST['acceso']."'
        where id=".$_POST['iduser']); 

    $response['success'] = true;
} catch (Exception $e) {
    $response['message'] = 'Error al actualizar el usuario: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>