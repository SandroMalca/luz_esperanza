<?php
include "./conexion.php";
date_default_timezone_set('America/Lima');

$conexion->query("insert into lista (nombre, descripcion, cantidad) values 
('" . mysqli_real_escape_string($conexion,$_POST['nombre']) . "', '" . mysqli_real_escape_string($conexion,$_POST['descripcion']) . "', 
'" . mysqli_real_escape_string($conexion,$_POST['cantidad']) . "')") or die($conexion->error);


header("Location: ../lista.php");
