<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

if (!isset($_POST['precio']) || !is_numeric($_POST['precio']) || $_POST['precio'] < 0) {
    header("Location: ../inventario.php?error=El precio debe ser un número válido");
    exit;
}

// Formatear el precio a dos decimales
$precio = number_format((float)$_POST['precio'], 2, '.', '');

// Verificar horarios seleccionados
if (!isset($_POST['horario']) || empty($_POST['horario']) || in_array('0', $_POST['horario'])) {
    // Si no hay horarios seleccionados o se seleccionó "Ninguno", establecer Lunes a Viernes
    $horarios = "1,2,3,4,5";
} else {
    // Si hay horarios seleccionados, usar esos
    $horarios = implode(',', $_POST['horario']);
}

$conexion->query("update producto set 
    nombre='".$_POST['nombre']."', 
    precio='".$precio."',
    horario='".$horarios."'
    where id=".$_POST['id']) 
or die($conexion->error); 

header("Location: ../inventario.php"); 
?>