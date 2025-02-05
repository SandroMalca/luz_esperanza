<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

$response = array('success' => false, 'message' => '');

try {
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
            AND status = 1");
        
        if ($check_doc->num_rows > 0) {
            $paciente = $check_doc->fetch_assoc();
            throw new Exception('Ya existe un paciente con este documento: ' . 
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
        AND status = 1");

    if ($check_nombre->num_rows > 0) {
        throw new Exception('Ya existe un paciente con el mismo nombre y especialidad');
    }

    // Asegurar que id_tipopersona sea 2 para pacientes
    $_POST['id_tipopersona'] = 2;

    // Insertar el paciente
    $conexion->query("INSERT INTO persona (
        nombre, ape1, ape2, nrodoc, id_tipodoc, fec_nac, 
        sexo, especialidad, estado_civil, id_tipopersona, status
    ) VALUES (
        '".$_POST['nombre']."',
        '".$_POST['ape1']."',
        '".$_POST['ape2']."',
        '".$_POST['nrodoc']."',
        '".$_POST['id_tipodoc']."',
        '".$_POST['fec_nac']."',
        '".$_POST['sexo']."',
        '".$_POST['especialidad']."',
        '".$_POST['estado_civil']."',
        '2',
        '1'
    )") or throw new Exception($conexion->error);

    // Obtener el ID del paciente recién insertado
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
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?>