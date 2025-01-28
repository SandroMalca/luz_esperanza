<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
$conexion -> query("delete from lista where id=".$_POST['idEliminar']);

header("Location: ../lista.php"); 
 ?>