<?php
include "php/conexion.php";
session_start();
error_reporting(0);
$varsesion = $_SESSION['acceso'];
$varsesion2 = $_SESSION['user'];
date_default_timezone_set('America/Lima');
if ($varsesion2 == null || $varsesion2 == '') {
	header("Location: logueo.php");
	die();
}

$fechaEntera = time();
$fecha = date('Y-m-d');
$dia = date("d", $fechaEntera);
$mes = date("m", $fechaEntera);

function nombremes($mes)
{
	setlocale(LC_TIME, 'spanish');
	$nombre = strftime("%B", mktime(0, 0, 0, $mes, 1, 2000));
	return $nombre;
}

$mesnombre = nombremes($mes);
$str = strtoupper($mesnombre);

$resultadoIngresoDia = $conexion->query("select SUM(pago.efectivo) as efectivo, SUM(pago.tarjeta) as tarjeta from pago where pago.fecha='" . $fecha . "' ");
$mostrarIngresoDia = mysqli_fetch_assoc($resultadoIngresoDia);

$resultadoEgresoDia = $conexion->query("select SUM(egreso.egreso) as egreso from egreso where egreso.fecha='" . $fecha . "' ");
$mostrarEgresoDia = mysqli_fetch_assoc($resultadoEgresoDia);

// Verificar y convertir a número los valores antes de sumar
$efectivo = floatval($mostrarIngresoDia['efectivo']) ?? 0;
$tarjeta = floatval($mostrarIngresoDia['tarjeta']) ?? 0;
$egreso = floatval($mostrarEgresoDia['egreso']) ?? 0;

$IngresoDia = $efectivo + $tarjeta - $egreso;

$resultadoIngresoMes = $conexion->query("select SUM(pago.efectivo) as efectivo, SUM(pago.tarjeta) as tarjeta from pago where month(pago.fecha)='" . $mes . "' ");
$mostrarIngresoMes = mysqli_fetch_assoc($resultadoIngresoMes);

$resultadoEgresoMes = $conexion->query("select SUM(egreso.egreso) as egreso from egreso where month(egreso.fecha)='" . $mes . "' ");
$mostrarEgresoMes = mysqli_fetch_assoc($resultadoEgresoMes);

$IngresoMes = $mostrarIngresoMes['efectivo'] + $mostrarIngresoMes['tarjeta'] - $mostrarEgresoMes['egreso'];

$resultadoPacienteDia = $conexion->query("select count(persona.id) as pacientes from persona where persona.id_tipopersona=2 and persona.fecha='" . $fecha . "' ");
$mostrarPacienteDia = mysqli_fetch_assoc($resultadoPacienteDia);

$resultadoPacienteMes = $conexion->query("select count(persona.id) as pacientes from persona where persona.id_tipopersona=2 and month(persona.fecha)='" . $mes . "' ");
$mostrarPacienteMes = mysqli_fetch_assoc($resultadoPacienteMes);

$resultadoHistoriaDia = $conexion->query("select count(historia.id) as historia from historia where historia.fecha='" . $fecha . "' ");
$mostrarHistoriaDia = mysqli_fetch_assoc($resultadoHistoriaDia);

$resultadoHistoriaMes = $conexion->query("select count(historia.id) as historia from historia where month(historia.fecha)='" . $mes . "' ");
$mostrarHistoriaMes = mysqli_fetch_assoc($resultadoHistoriaMes);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Consultorio Medicos Especializados Luz de Esperanza</title>
	<meta name="description" content="Consultorio Medicos Especializados Luz de Esperanza">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/estilos.css">
	<link rel="icon" href="img/LOGO.png" type="image/x-icon">
</head>

<body>
	<?php include "./layouts/header.php" ?>

	<section>
		<br><br>
		<?php
		if ($varsesion != 'Asistente') {

		?>

			<div class="container" align="center">
				<div class="row">
					<div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 grupo">
						<a href="pacientemes.php">
							<div class="cuadrado" id="cuadrado3" align="center">
								Pacientes nuevos del mes <br>
								<?php echo $mostrarPacienteMes['pacientes'] ?>
							</div>
						</a>
					</div>
					<div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 grupo">
						<a href="historiadia.php">
							<div class="cuadrado" id="cuadrado5" align="center">
								Historias nuevas del día <br>
								<?php echo $mostrarHistoriaDia['historia'] ?>
							</div>
						</a>
					</div>
					<div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 grupo">
						<a href="historiames.php">
							<div class="cuadrado" id="cuadrado6" align="center">
								Historias nuevas del mes <br>
								<?php echo $mostrarHistoriaMes['historia'] ?>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<?php if ($varsesion != 'Admin') { ?>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 grupo">
							<a href="ingresodia.php">
								<div class="cuadrado" id="cuadrado1" align="center">
									Ingreso del día <br>
									S/ <?php echo $IngresoDia; ?>
								</div>
							</a>
						</div>
						<div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 grupo">
							<a href="ingresomes.php">
								<div class="cuadrado" id="cuadrado2" align="center">
									Ingreso del mes <br>
									S/ <?php echo $IngresoMes; ?>
								</div>
							</a>
						</div>
					<?php } ?>
					<div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 grupo">
						<a href="pacientedia.php">
							<div class="cuadrado" id="cuadrado4" align="center">
								Pacientes nuevos del día <br>
								<?php echo $mostrarPacienteDia['pacientes'] ?>
							</div>
						</a>
					</div>
				</div>
			</div>

		<?php
		} else {
		?>
			<div align="center" class="container">
				<img src="img/frase.jpg" alt="" class="w-100">
			</div>

		<?php } ?>

	</section>

	<script src="js/jquery-3.5.1.slim.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.js"></script>
</body>

</html>