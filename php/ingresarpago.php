<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

if(isset($_POST['fecha']) && isset($_POST['id_cliente'])) {
    $conexion->query("insert into pago (
        id,
        fecha,
        id_cliente,
        efectivo,
        tarjeta,
        descripcion,
        status
    ) values (
        NULL,
        '".mysqli_real_escape_string($conexion,$_POST['fecha'])."',
        '".mysqli_real_escape_string($conexion,$_POST['id_cliente'])."',
        '".mysqli_real_escape_string($conexion,$_POST['efectivo'])."',
        '".mysqli_real_escape_string($conexion,$_POST['tarjeta'])."',
        '".mysqli_real_escape_string($conexion,$_POST['descripcion'])."',
        '1'
    )") or die($conexion->error);

    header("Location: ../pago.php?id=".$_POST['id_cliente']);
} else {
    header("Location: ../pago.php?id=".$_POST['id_cliente']."&error=Favor de llenar todos los campos");
}
?>