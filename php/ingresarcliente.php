<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    // Asegurar que id_tipopersona sea 2 para clientes
    $_POST['id_tipopersona'] = 2;

    // Insertar el cliente
    $conexion->query("INSERT INTO persona (
        nombre, ape1, ape2, nrodoc, id_tipodoc, fec_nac, 
        sexo, estado_civil, id_tipopersona, status
    ) VALUES (
        '".$_POST['nombre']."',
        '".$_POST['ape1']."',
        '".$_POST['ape2']."',
        '".$_POST['nrodoc']."',
        '".$_POST['id_tipodoc']."',
        '".$_POST['fec_nac']."',
        '".$_POST['sexo']."',
        '".$_POST['estado_civil']."',
        '2',
        '1'
    )") or throw new Exception($conexion->error);

    // Obtener el ID del cliente recién insertado
    $id_cliente = $conexion->insert_id;

    // Insertar los signos vitales
    if ($id_cliente) {
        $conexion->query("INSERT INTO exploracion_fisica (
            id_historia, pad, pas, spo2, fc, temp, peso, talla
        ) VALUES (
            '".$id_cliente."',
            '".$_POST['pad']."',
            '".$_POST['pas']."',
            '".$_POST['spo2']."',
            '".$_POST['fc']."',
            '".$_POST['temp']."',
            '".$_POST['peso']."',
            '".$_POST['talla']."'
        )") or throw new Exception($conexion->error);
    }

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Error al ingresar el cliente: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>