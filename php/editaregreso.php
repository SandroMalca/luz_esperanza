<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update egreso set 
 		egreso='".$_POST['egreso']."', 
 		descripcion='".$_POST['descripcion']."'
 		where id=".$_POST['id']); 

	header("Location: ../caja.php"); 




	
		
	

 ?>