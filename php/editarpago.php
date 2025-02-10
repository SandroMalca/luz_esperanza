<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update pago set 
 		fecha='".mysqli_real_escape_string($conexion,$_POST['fecha'])."', 
 		efectivo='".mysqli_real_escape_string($conexion,$_POST['efectivo'])."', 
 		tarjeta='".mysqli_real_escape_string($conexion,$_POST['tarjeta'])."', 
 		descripcion='".mysqli_real_escape_string($conexion,$_POST['descripcion'])."'
 		where id=".mysqli_real_escape_string($conexion,$_POST['id'])); 

	header("Location: ../pago.php?id=".$_POST['id_cliente']."&edit=Etiqueta modificada correctamente"); 




	
		
	

 ?>