<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
	$conexion -> query("update pago set 
 		fecha='".$_POST['fecha']."', 
 		efectivo='".$_POST['efectivo']."', 
 		tarjeta='".$_POST['tarjeta']."', 
 		descripcion='".$_POST['descripcion']."'
 		where id=".$_POST['id']); 

	header("Location: ../pago.php?id=".$_POST['id_cliente']."&edit=Etiqueta modificada correctamente"); 




	
		
	

 ?>