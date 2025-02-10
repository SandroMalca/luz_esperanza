<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update logueo set 
 		password='".mysqli_real_escape_string($conexion,$_POST['password'])."' 
 		where id=".mysqli_real_escape_string($conexion,$_POST['iduser'])); 

	header("Location: ../users.php"); 




	
		
	

 ?>