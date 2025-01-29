<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    // Asegurar que id_tipopersona sea 1 para doctores
    $_POST['id_tipopersona'] = 1;

    $conexion->query("update persona set 
        nombre='".$_POST['nombre']."', 
        ape1='".$_POST['ape1']."', 
        ape2='".$_POST['ape2']."', 
        cmp='".$_POST['cmp']."',
        especialidad='".$_POST['especialidad']."',
        id_tipopersona='1'
        where id=".$_POST['id']); 

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = 'Error al actualizar el doctor: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>