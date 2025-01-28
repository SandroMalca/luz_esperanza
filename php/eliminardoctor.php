<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

if(isset($_POST['id'])) {
    // Eliminar el doctor de la tabla persona
    $conexion->query("DELETE FROM persona WHERE id=".$_POST['id']." AND id_tipopersona=1") 
    or die($conexion->error);
    
    echo "success";
} else {
    header("Location: ../users.php?error=No se pudo eliminar el doctor");
}
?> 