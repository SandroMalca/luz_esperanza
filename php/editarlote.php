<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update lote set 
 		fec_ven='".$_POST['fec_ven']."', 
 		cantidad='".$_POST['cantidad']."',
 		lote='".$_POST['lote']."' 
 		where id=".$_POST['id']); 

	header("Location: ../lote.php?id=".$_POST['id_producto']); 




	
		
	

 ?>