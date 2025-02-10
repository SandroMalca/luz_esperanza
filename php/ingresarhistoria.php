<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
if (isset($_POST['fecha'])) {
    $fecha = date('Y-m-d');

    $conexion->query("insert into historia (
        fecha, 
        id_cliente,
        motivo,
        enfermedad_actual,
        antec_personales,
        antec_familiar,
        exam_fisico,
        diag_presuntivo,
        exam_auxiliar,
        laboratorio,
        otros,
        diag_definitivo,
        tratamiento,
        fec_creacion,
        id_doctor
    ) values (
        '".mysqli_real_escape_string($conexion,$_POST['fecha'])."',
        '".mysqli_real_escape_string($conexion,$_POST['id_cliente'])."',
        '".mysqli_real_escape_string($conexion,$_POST['motivo'])."',
        '".mysqli_real_escape_string($conexion,$_POST['enfermedad_actual'])."',
        '".mysqli_real_escape_string($conexion,$_POST['antec_personales'])."',
        '".mysqli_real_escape_string($conexion,$_POST['antec_familiar'])."',
        '".mysqli_real_escape_string($conexion,$_POST['exam_fisico'])."',
        '".mysqli_real_escape_string($conexion,$_POST['diag_presuntivo'])."',
        '".mysqli_real_escape_string($conexion,$_POST['exam_auxiliar'])."',
        '".mysqli_real_escape_string($conexion,$_POST['laboratorio'])."',
        '".mysqli_real_escape_string($conexion,$_POST['otros'])."',
        '".mysqli_real_escape_string($conexion,$_POST['diag_definitivo'])."',
        '".mysqli_real_escape_string($conexion,$_POST['tratamiento'])."',
        '$fecha',
        '".mysqli_real_escape_string($conexion,$_POST['id_doctor'])."'
    )") or die($conexion->error);

    header("Location: ../historia.php?id=".$_POST['id_cliente']);
} else {
    header("Location: ../historia.php?id=".$_POST['id_cliente']."&error=Favor ingresar fecha");
}
?>