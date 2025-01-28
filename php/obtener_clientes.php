<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

// Obtener todos los clientes
$resultado = $conexion->query("
    SELECT 
        p.*,
        ef.pad, ef.pas, ef.spo2, ef.fc, ef.temp, ef.peso, ef.talla,
        DATE_FORMAT(p.fec_nac, '%Y-%m-%d') as fec_nac,
        YEAR(CURDATE())-YEAR(p.fec_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(p.fec_nac,'%m-%d'), 0, -1) as edad,
        tipo_doc.nombre as tipodoc
    FROM persona p
    LEFT JOIN exploracion_fisica ef ON p.id = ef.id_historia
    LEFT JOIN tipo_doc on tipo_doc.id=p.id_tipodoc
    WHERE p.id_tipopersona = 2 AND p.status = 1
    ORDER BY p.id DESC
") or die($conexion->error);

$clientes = array();
while($f = mysqli_fetch_array($resultado)) {
    $clientes[] = $f;
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($clientes);
?> 