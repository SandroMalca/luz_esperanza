<?php 
include "./conexion.php";date_default_timezone_set('America/Lima');
 if ( isset($_POST['egreso']) && isset($_POST['descripcion']) ) {

 	$fecha = date('Y-m-d');

			$conexion -> query("insert into egreso (fecha, egreso, descripcion) 
				values ('$fecha', '".mysqli_real_escape_string($conexion,$_POST['egreso'])."', '".mysqli_real_escape_string($conexion,$_POST['descripcion'])."')") or die($conexion->error);


			header("Location: ../caja.php");

		} else {
			header("Location: ../caja.php?error=Favor de llenar todos los campos obligatorios");
		}


 ?>