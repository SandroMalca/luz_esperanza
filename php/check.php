<?php 
session_start();
include "./conexion.php";
date_default_timezone_set('America/Lima');

$user = $_POST['user'];
$reemplazaruser = str_replace("'","\'",$user);
$password = $_POST['password'];
$reemplazarpassword = str_replace("'","\'",$password);

if (isset($_POST['user']) && isset($_POST['password'])) {
	$resultado = $conexion -> query ("select * from logueo where user='".$reemplazaruser."' and password='".$reemplazarpassword."' limit 1") or die($conexion -> error);
	$mostrar=mysqli_fetch_assoc($resultado);
	if (mysqli_num_rows($resultado)>0) {
		$_SESSION['acceso']=$mostrar['acceso'];
		$_SESSION['user']=$mostrar['user'];
		header("Location: ../index.php");
	} else {
		header("Location: ../logueo.php?error=Credenciales incorrectas");
	}

} else{
	header("../logueo.php");
}
 ?>}