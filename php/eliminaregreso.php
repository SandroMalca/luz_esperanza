<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
$conexion->query("delete from egreso where id=".$_POST['id']);
?>