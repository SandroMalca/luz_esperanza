<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
if (isset($_POST['nombre']) && isset($_POST['precio'])) {
    // Validar que el precio sea un número válido
    if (!is_numeric($_POST['precio']) || $_POST['precio'] < 0) {
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

    $conexion->query("insert into producto (nombre, precio, horario) 
        values ('".mysqli_real_escape_string($conexion,$_POST['nombre'])."', '".mysqli_real_escape_string($conexion,$precio)."', '".mysqli_real_escape_string($conexion,$horarios)."')") 
        or die($conexion->error);

    header("Location: ../inventario.php");
} else {
    header("Location: ../inventario.php?error=Favor de llenar todos los campos obligatorios");
}
?>