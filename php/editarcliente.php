<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    if (!isset($_POST['id'])) {
        throw new Exception('ID no proporcionado');
    }

    // Actualizar datos del cliente
    $conexion->query("UPDATE persona SET 
        nombre = '".$_POST['nombre']."',
        ape1 = '".$_POST['ape1']."',
        ape2 = '".$_POST['ape2']."',
        nrodoc = '".$_POST['nrodoc']."',
        id_tipodoc = '".$_POST['id_tipodoc']."',
        fec_nac = '".$_POST['fec_nac']."',
        sexo = '".$_POST['sexo']."',
        estado_civil = '".$_POST['estado_civil']."',
        id_tipopersona = '2'
        WHERE id = ".$_POST['id']) 
    or throw new Exception($conexion->error);

    // Actualizar signos vitales
    $conexion->query("UPDATE exploracion_fisica SET 
        pad = '".$_POST['pad']."',
        pas = '".$_POST['pas']."',
        spo2 = '".$_POST['spo2']."',
        fc = '".$_POST['fc']."',
        temp = '".$_POST['temp']."',
        peso = '".$_POST['peso']."',
        talla = '".$_POST['talla']."'
        WHERE id_historia = ".$_POST['id'])
    or throw new Exception($conexion->error);

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Error al actualizar el cliente: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>