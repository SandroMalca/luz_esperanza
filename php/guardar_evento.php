<?php
include "./conexion.php";
date_default_timezone_set('America/Lima');
session_start();

$response = array('success' => false, 'message' => '');

try {
    // Validar campos requeridos
    if (empty($_POST['paciente'])) {
        throw new Exception('Debe seleccionar un paciente');
    }
    if (empty($_POST['doctor'])) {
        throw new Exception('Debe seleccionar un doctor');
    }
    if (empty($_POST['fecha'])) {
        throw new Exception('La fecha es requerida');
    }
    if (empty($_POST['horario'])) {
        throw new Exception('Debe seleccionar un horario');
    }

    // Preparar los datos
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $id_paciente = $_POST['paciente'];
    $id_doctor = $_POST['doctor'];
    $fecha = $_POST['fecha'];
    list($hora_inicio, $hora_fin) = explode('-', $_POST['horario']);
    
    // Formatear fechas completas
    $fecha_inicio = $fecha . ' ' . $hora_inicio . ':00';
    $fecha_fin = $fecha . ' ' . $hora_fin . ':00';

    // Verificar disponibilidad del horario
    $query_disponibilidad = "SELECT id FROM eventos 
        WHERE id_doctor = '$id_doctor' 
        AND fecha = '$fecha' 
        AND horario = '".$_POST['horario']."'";
    
    if ($id) {
        $query_disponibilidad .= " AND id != $id";
    }

    $check_disponibilidad = $conexion->query($query_disponibilidad);
    if ($check_disponibilidad->num_rows > 0) {
        throw new Exception('El horario seleccionado no está disponible para este doctor');
    }

    // Obtener datos del paciente
    $query_paciente = $conexion->query("SELECT CONCAT(ape1, ' ', COALESCE(ape2, ''), ', ', nombre) as nombre_completo FROM persona WHERE id = '$id_paciente'");
    $query_doctor = $conexion->query("SELECT CONCAT(ape1, ' ', COALESCE(ape2, ''), ' ') as nombre_completo FROM persona WHERE id = '$id_doctor'");
    $paciente = mysqli_fetch_assoc($query_paciente);
    $doctor=mysqli_fetch_assoc($query_doctor);
    $titulo = "Doctor: " . $doctor['nombre_completo'] . "Paciente: " . $paciente['nombre_completo'];

    if ($id) {
        // Actualizar cita existente
        $query = "UPDATE eventos SET 
            titulo = '$titulo',
            id_paciente = '$id_paciente',
            id_doctor = '$id_doctor',
            fecha = '$fecha',
            horario = '".$_POST['horario']."',
            fecha_inicio = '$fecha_inicio',
            fecha_fin = '$fecha_fin'
            WHERE id = $id";
    } else {
        // Crear nueva cita
        $query = "INSERT INTO eventos (
            titulo, id_paciente, id_doctor, fecha, horario, fecha_inicio, fecha_fin, usuario
        ) VALUES (
            'mysqli_real_escape_string($conexion,$titulo)', 'mysqli_real_escape_string($conexion,$id_paciente)', 'mysqli_real_escape_string($conexion,$id_doctor)', 'mysqli_real_escape_string($conexion,$fecha)
            ', '".mysqli_real_escape_string($conexion,$_POST['horario'])."', 
            'mysqli_real_escape_string($conexion,$fecha_inicio)', 'mysqli_real_escape_string($conexion,$fecha_fin)', '".mysqli_real_escape_string($conexion,$_SESSION['user'])."'
        )";
    }

    if (!$conexion->query($query)) {
        throw new Exception($conexion->error);
    }

    $response['success'] = true;
    
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($response);
?> 