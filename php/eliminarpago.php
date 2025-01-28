<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
$conexion -> query("update pago set status = 0 where id=".$_POST['id']);

header("Location: ../pago.php?id=".$_POST['id_cliente']); 

 ?>