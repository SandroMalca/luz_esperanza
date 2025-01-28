<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update lista set 
 		nombre='".$_POST['nombre']."',
 		descripcion='".$_POST['descripcion']."',
 		cantidad='".$_POST['cantidad']."' 
 		where id=".$_POST['id']); 

	header("Location: ../lista.php"); 




	
		
	

 ?>