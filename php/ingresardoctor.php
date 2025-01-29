<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

if (isset($_POST['nombre']) && isset($_POST['ape1'])) {
    $fecha = date('Y-m-d');

    // Asegurar que id_tipopersona sea 1 para doctores
    $_POST['id_tipopersona'] = 1;

    $conexion->query("insert into persona (
        nombre, 
        ape1, 
        ape2, 
        cmp,
        id_tipopersona,
        fecha,
        especialidad
    ) values (
        '".$_POST['nombre']."',
        '".$_POST['ape1']."',
        '".$_POST['ape2']."',
        '".$_POST['cmp']."',
        '1',
        '$fecha',
        '".$_POST['especialidad']."'
    )") or die($conexion->error);

    header("Location: ../users.php  ");
} else {
    header("Location: ../users.php?error=Favor de llenar todos los campos obligatorios");
}
?>