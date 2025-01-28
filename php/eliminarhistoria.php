<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
$conexion -> query("delete from historia where id=".$_POST['id']);

echo "Historia eliminada"; 

 ?>