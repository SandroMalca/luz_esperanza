<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    if (!isset($_POST['id'])) {
        throw new Exception('ID no proporcionado');
    }

    // Actualizar el status a 0 en lugar de eliminar
    $conexion->query("UPDATE persona SET status = 0 WHERE id = " . $_POST['id']) 
        or throw new Exception($conexion->error);

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Error al desactivar el cliente: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>