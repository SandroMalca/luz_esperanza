<?php 
include "./conexion.php"; date_default_timezone_set('America/Lima');
 if ( isset($_POST['fecha']) && isset($_POST['tratamiento']) ) {

			$conexion -> query("insert into tratamiento (id_historia, fecha, tratamiento, cd, prox_cita) values ('".$_POST['id_historia']."', '".$_POST['fecha']."', '"
			.$_POST['tratamiento']."', '".$_POST['cd']."', '".$_POST['prox_cita']."')") or die($conexion->error);


			header("Location: ../tratamiento.php?id=".$_POST['id_historia']);

		} else {
			header("Location: ../tratamiento.php?id=".$_POST['id_historia']."&error=Favor de llenar todos los campos obligatorios");
		}


 ?>