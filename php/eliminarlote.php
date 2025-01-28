<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
$conexion -> query("delete from lote where id=".$_POST['id']);

echo "Lote eliminado"; 

 ?>