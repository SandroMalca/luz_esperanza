<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    // Validar campos requeridos
    if (!isset($_POST['user']) || empty($_POST['user'])) {
        throw new Exception('El usuario es requerido');
    }

    if (!isset($_POST['password']) || empty($_POST['password'])) {
        throw new Exception('La contraseña es requerida');
    }

    if (!isset($_POST['acceso']) || empty($_POST['acceso'])) {
        throw new Exception('El tipo de acceso es requerido');
    }

    // Verificar si el usuario ya existe
    $check = $conexion->query("SELECT id FROM logueo WHERE user = '".$_POST['user']."'");
    if ($check->num_rows > 0) {
        throw new Exception('El usuario ya existe');
    }

    // Insertar nuevo usuario
    $conexion->query("INSERT INTO logueo (
        user, 
        password, 
        acceso
    ) VALUES (
        '".$_POST['user']."',
        '".$_POST['password']."',
        '".$_POST['acceso']."'
    )") or throw new Exception($conexion->error);

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>