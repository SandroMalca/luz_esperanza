<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');
 if ( isset($_POST['user']) && isset($_POST['password']) ) {

 	$fecha = date('Y-m-d');

			$conexion -> query("insert into logueo (user, password, acceso) values ('".$_POST['user']."', '".$_POST['password']."', '".$_POST['acceso']."')") or die($conexion->error);


			header("Location: ../users.php?success=Usuario ingresado correctamente");

		} else {
			header("Location: ../users.php?error=Favor de llenar todos los campos obligatorios");
		}


 ?>