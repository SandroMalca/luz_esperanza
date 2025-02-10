<?php
include "./conexion.php";
date_default_timezone_set('America/Lima');
session_start();

$response = array('success' => false, 'message' => '');

try {
    if (!isset($_POST['id'])) {
        throw new Exception('ID no proporcionado');
    }

    $id = $_POST['id'];
    $usuario = $_SESSION['user'];
    $acceso = $_SESSION['acceso'];

    // Verificar que el usuario tenga permiso para eliminar el evento
    if ($acceso != 'Superadmin') {
        $check = $conexion->query("SELECT id FROM eventos WHERE id = $id AND usuario = '$usuario'");
        if ($check->num_rows == 0) {
            throw new Exception('No tiene permiso para eliminar este evento');
        }
    }

    // Eliminar el evento
    if (!$conexion->query("DELETE FROM eventos WHERE id = $id")) {
        throw new Exception($conexion->error);
    }

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?> 