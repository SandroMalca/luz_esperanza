<?php
include "./conexion.php";
date_default_timezone_set('America/Lima');

$conexion->query("insert into lista (nombre, descripcion, cantidad) values ('" . $_POST['nombre'] . "', '" . $_POST['descripcion'] . "', '" . $_POST['cantidad'] . "')") or die($conexion->error);


header("Location: ../lista.php");
