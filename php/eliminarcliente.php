<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array();

if(isset($_POST['id'])){
    // En lugar de eliminar, actualizamos el status a 0
    $conexion->query("UPDATE persona SET status = '0' WHERE id=".$_POST['id']) 
    or die($conexion->error);
    
    $response['success'] = true;
    
} else {
    $response['success'] = false;
    $response['message'] = 'Error al desactivar el cliente';
}

echo json_encode($response);

 ?>