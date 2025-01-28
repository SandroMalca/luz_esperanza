<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    // Asegurarse de que la fecha de nacimiento esté en el formato correcto
    $fec_nac = date('Y-m-d', strtotime($_POST['fec_nac']));

    // Actualizar datos del paciente
    $conexion->query("update persona set 
        nombre='".$_POST['nombre']."', 
        ape1='".$_POST['ape1']."', 
        ape2='".$_POST['ape2']."', 
        nrodoc='".$_POST['nrodoc']."', 
        id_tipodoc='".$_POST['id_tipodoc']."', 
        fec_nac='".$fec_nac."',
        sexo='".$_POST['sexo']."',
        estado_civil='".$_POST['estado_civil']."',
        id_tipopersona='".$_POST['id_tipopersona']."'
        where id=".$_POST['id']); 

    // Actualizar datos de exploración física
    $conexion->query("update exploracion_fisica set 
        pad='".$_POST['pad']."',
        pas='".$_POST['pas']."',
        spo2='".$_POST['spo2']."',
        fc='".$_POST['fc']."',
        temp='".$_POST['temp']."',
        peso='".$_POST['peso']."',
        talla='".$_POST['talla']."'
        where id_historia=".$_POST['id']);

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Error al actualizar el cliente: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>