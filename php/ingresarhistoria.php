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
        '".$_POST['fecha']."',
        '".$_POST['id_cliente']."',
        '".$_POST['motivo']."',
        '".$_POST['enfermedad_actual']."',
        '".$_POST['antec_personales']."',
        '".$_POST['antec_familiar']."',
        '".$_POST['exam_fisico']."',
        '".$_POST['diag_presuntivo']."',
        '".$_POST['exam_auxiliar']."',
        '".$_POST['laboratorio']."',
        '".$_POST['otros']."',
        '".$_POST['diag_definitivo']."',
        '".$_POST['tratamiento']."',
        '$fecha',
        '".$_POST['id_doctor']."'
    )") or die($conexion->error);

    header("Location: ../historia.php?id=".$_POST['id_cliente']);
} else {
    header("Location: ../historia.php?id=".$_POST['id_cliente']."&error=Favor ingresar fecha");
}
?>