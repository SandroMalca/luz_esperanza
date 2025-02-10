<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update egreso set 
 		egreso='".mysqli_real_escape_string($conexion,$_POST['egreso'])."', 
 		descripcion='".mysqli_real_escape_string($conexion,$_POST['descripcion'])."'
 		where id=".mysqli_real_escape_string($conexion,$_POST['id'])); 

	header("Location: ../caja.php"); 




	
		
	

 ?>