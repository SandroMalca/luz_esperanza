<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

// Obtener todos los doctores
$resultado = $conexion->query("SELECT 
    persona.*, 
    DATE_FORMAT(persona.fec_nac, '%Y-%m-%d') as fec_nac,
    YEAR(CURDATE())-YEAR(persona.fec_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(persona.fec_nac,'%m-%d'), 0, -1) as edad,
    tipo_doc.nombre as tipodoc
    FROM persona 
    LEFT JOIN tipo_doc on tipo_doc.id=persona.id_tipodoc 
    WHERE persona.id_tipopersona='1' 
    ORDER BY ape1, ape2, nombre ASC");

$doctores = array();
while($doctor = mysqli_fetch_assoc($resultado)) {
    $doctores[] = $doctor;
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($doctores);
?> 