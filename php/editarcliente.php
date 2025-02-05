<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
    if (!isset($_POST['id'])) {
        throw new Exception('ID no proporcionado');
    }

    // Validar campos requeridos
    if (empty($_POST['nombre']) || empty($_POST['ape1'])) {
        throw new Exception('El nombre y apellido paterno son requeridos');
    }

    // Verificar duplicidad por número de documento si existe
    if (!empty($_POST['nrodoc'])) {
        $check_doc = $conexion->query("SELECT id, nombre, ape1, ape2, especialidad FROM persona 
            WHERE nrodoc = '".$_POST['nrodoc']."' 
            AND id_tipodoc = '".$_POST['id_tipodoc']."'
            AND id_tipopersona = '2'
            AND id != '".$_POST['id']."'
            AND status = 1");
        
        if ($check_doc->num_rows > 0) {
            $paciente = $check_doc->fetch_assoc();
            throw new Exception('Ya existe otro paciente con este documento: ' . 
                $paciente['nombre'] . ' ' . $paciente['ape1'] . ' ' . $paciente['ape2'] . 
                ' (Especialidad: ' . $paciente['especialidad'] . ')');
        }
    }

    // Verificar duplicidad por nombre completo y especialidad
    $check_nombre = $conexion->query("SELECT id FROM persona 
        WHERE LOWER(nombre) = LOWER('".$_POST['nombre']."')
        AND LOWER(ape1) = LOWER('".$_POST['ape1']."')
        AND LOWER(ape2) = LOWER('".$_POST['ape2']."')
        AND especialidad = '".$_POST['especialidad']."'
        AND id_tipopersona = '2'
        AND id != '".$_POST['id']."'
        AND status = 1");

    if ($check_nombre->num_rows > 0) {
        throw new Exception('Ya existe otro paciente con el mismo nombre y especialidad');
    }

    // Actualizar datos del paciente
    $conexion->query("UPDATE persona SET 
        nombre = '".$_POST['nombre']."',
        ape1 = '".$_POST['ape1']."',
        ape2 = '".$_POST['ape2']."',
        nrodoc = '".$_POST['nrodoc']."',
        id_tipodoc = '".$_POST['id_tipodoc']."',
        fec_nac = '".$_POST['fec_nac']."',
        sexo = '".$_POST['sexo']."',
        estado_civil = '".$_POST['estado_civil']."',
        especialidad = '".$_POST['especialidad']."',
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
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>