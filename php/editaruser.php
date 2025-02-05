<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    if (!isset($_POST['id'])) {
        throw new Exception('ID no proporcionado');
    }

    if (!isset($_POST['user']) || empty($_POST['user'])) {
        throw new Exception('Usuario no proporcionado');
    }

    if (!isset($_POST['acceso']) || empty($_POST['acceso'])) {
        throw new Exception('Tipo de acceso no proporcionado');
    }

    $conexion->query("UPDATE logueo SET 
        user = '".$_POST['user']."', 
        acceso = '".$_POST['acceso']."'
        WHERE id = ".$_POST['id']) 
    or throw new Exception($conexion->error);

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Error al actualizar el usuario: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>