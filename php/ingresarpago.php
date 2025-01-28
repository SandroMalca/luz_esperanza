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
        '".$_POST['fecha']."',
        '".$_POST['id_cliente']."',
        '".$_POST['efectivo']."',
        '".$_POST['tarjeta']."',
        '".$_POST['descripcion']."',
        '1'
    )") or die($conexion->error);

    header("Location: ../pago.php?id=".$_POST['id_cliente']);
} else {
    header("Location: ../pago.php?id=".$_POST['id_cliente']."&error=Favor de llenar todos los campos");
}
?>