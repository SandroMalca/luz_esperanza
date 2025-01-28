<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
if (isset($_POST['nombre']) && isset($_POST['ape1'])) {
    $fecha = date('Y-m-d');
    
    // Asegurarse de que la fecha de nacimiento esté en el formato correcto
    $fec_nac = date('Y-m-d', strtotime($_POST['fec_nac']));

    // Insertar datos del paciente
    $conexion->query("insert into persona (
        id,
        nombre, 
        ape1, 
        ape2, 
        nrodoc,
        id_tipodoc,
        fec_nac,
        sexo,
        estado_civil,
        id_tipopersona,
        fecha,
        status
    ) values (
        NULL,
        '".$_POST['nombre']."',
        '".$_POST['ape1']."',
        '".$_POST['ape2']."',
        '".$_POST['nrodoc']."',
        '".$_POST['id_tipodoc']."',
        '".$fec_nac."',
        '".$_POST['sexo']."',
        '".$_POST['estado_civil']."',
        '".$_POST['id_tipopersona']."',
        '$fecha',
        '1'
    )") or die($conexion->error);

    // Obtener el ID del paciente recién insertado
    $id_paciente = $conexion->insert_id;

    // Insertar datos de exploración física
    $conexion->query("insert into exploracion_fisica (
        id_historia,
        id_tratamiento,
        pad,
        pas,
        spo2,
        fc,
        temp,
        peso,
        talla
    ) values (
        '".$id_paciente."',
        '0',
        '".$_POST['pad']."',
        '".$_POST['pas']."',
        '".$_POST['spo2']."',
        '".$_POST['fc']."',
        '".$_POST['temp']."',
        '".$_POST['peso']."',
        '".$_POST['talla']."'
    )") or die($conexion->error);

    header("Location: ../cliente.php");
} else {
    header("Location: ../cliente.php?error=Favor de llenar todos los campos obligatorios");
}
?>