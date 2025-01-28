<?php 
date_default_timezone_set('America/Lima');
$servidor="localhost";
$nombreBD="luz_esperanza";
$usuario="root";
$pass="";
$conexion=new mysqli($servidor, $usuario, $pass, $nombreBD);
if($conexion -> connect_error){
	die("No se pudo conectar");
}

?>