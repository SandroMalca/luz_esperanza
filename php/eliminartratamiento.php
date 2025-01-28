<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
echo $_POST['idHistoriaEliminar'];

$conexion -> query("delete from tratamiento where id=".$_POST['idEliminar']);

header("Location: ../tratamiento.php?id=".$_POST['idHistoriaEliminar']);

 ?>