<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
session_start();

$varsesion = $_SESSION['acceso'];
$varsesion2 = $_SESSION['user'];

$clientes = array();

// Obtener la especialidad del usuario logueado
$especialidad_usuario = '';
if ($varsesion != 'Superadmin') {
    // Mapeo de usuarios a especialidades
    $especialidades_map = array(
		'cirugia' => 'Cirugía General',
		'derma' => 'Dermatología',
		'endo'=>'Endocrinología',
		'gastro' => 'Gastroenterología',
		'medicina' => 'Medicina General',
		'neuro' => 'Neurología',
		'nutricion' => 'Nutrición',
		'obstetricia' => 'Obstetricia',
		'pediatria' => 'Pediatría',
		'trauma' => 'Traumatología',
		'urologia' => 'Urología',
	);
    
    // Obtener la especialidad basada en el nombre de usuario
    $especialidad_usuario = isset($especialidades_map[$varsesion2]) ? $especialidades_map[$varsesion2] : '';
}

// Construir la consulta base
$query = "SELECT 
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
    WHERE persona.id_tipopersona='2' AND persona.status = 1";

// Agregar filtro por especialidad si no es Superadmin
if ($varsesion != 'Superadmin' && !empty($especialidad_usuario)) {
    $query .= " AND persona.especialidad = '".$especialidad_usuario."'";
}

$query .= " ORDER BY persona.id DESC";

$resultado = $conexion->query($query);

while ($cliente = mysqli_fetch_assoc($resultado)) {
    $clientes[] = $cliente;
}

// Prevenir caché
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
header('Content-Type: application/json');
echo json_encode($clientes);
?> 