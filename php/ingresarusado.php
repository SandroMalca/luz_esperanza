<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update lote set 
 		usado='".$_POST['usado']."'
 		where id=".$_POST['id']); 

	header("Location: ../lote.php?id=".$_POST['id_producto']); 




	
		
	

 ?>