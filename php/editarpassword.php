<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update logueo set 
 		password='".$_POST['password']."' 
 		where id=".$_POST['iduser']); 

	header("Location: ../users.php"); 




	
		
	

 ?>