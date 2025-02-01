<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$clientes = array();
$resultado = $conexion->query("SELECT 
    persona.*, 
    YEAR(CURDATE())-YEAR(persona.fec_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(persona.fec_nac,'%m-%d'), 0, -1) as edad,
    tipo_doc.nombre as tipodoc,
    exploracion_fisica.pad,
    exploracion_fisica.pas,
    exploracion_fisica.spo2,
    exploracion_fisica.fc,
    exploracion_fisica.temp,
    exploracion_fisica.peso,
    exploracion_fisica.talla
    FROM persona 
    LEFT JOIN tipo_doc ON tipo_doc.id=persona.id_tipodoc 
    LEFT JOIN exploracion_fisica ON exploracion_fisica.id_historia=persona.id
    WHERE persona.id_tipopersona='2' AND persona.status = 1
    ORDER BY persona.id DESC");

while ($cliente = mysqli_fetch_assoc($resultado)) {
    $clientes[] = $cliente;
}

header('Content-Type: application/json');
echo json_encode($clientes);
?> 