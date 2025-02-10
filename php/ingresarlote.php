<?php 
include "./conexion.php";date_default_timezone_set('America/Lima');
 if ( isset($_POST['cantidad']) ) {

			$conexion -> query("insert into lote (fec_ven, cantidad, usado, lote, id_producto) values 
			('".$_POST['fec_ven']."', '".$_POST['cantidad']."', 0, '".$_POST['lote']."', '".$_POST['id']."')") or die($conexion->error);


			header("Location: ../lote.php?id=".$_POST['id']);

		} else {
			header("Location: ../lote.php?id=".$_POST['id']."&error=Favor de llenar todos los campos obligatorios");
		}


 ?>