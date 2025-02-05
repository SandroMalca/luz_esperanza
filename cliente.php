<?php
include "php/conexion.php";
date_default_timezone_set('America/Lima');
session_start();
error_reporting(0);
$varsesion = $_SESSION['acceso'];
$varsesion2 = $_SESSION['user'];

if ($varsesion2 == null || $varsesion2 == '') {
	header("Location: logueo.php");
	die();
}

// Obtener la especialidad del usuario logueado
$especialidad_usuario = '';
if ($varsesion != 'Superadmin') {
	// Mapeo de usuarios a especialidades
	$especialidades_map = array(
		'cirugia' => 'Cirugía General',
		'derma' => 'Dermatología',
		'endo'=>'Endocrinología',
		'gastro' => 'Gastroenterología',
		'medicina' => 'Medicina General',
		'neuro' => 'Neurología',
		'nutricion' => 'Nutrición',
		'obstetricia' => 'Obstetricia',
		'pediatria' => 'Pediatría',
		'trauma' => 'Traumatología',
		'urologia' => 'Urología',
	);
	
	// Obtener la especialidad basada en el nombre de usuario
	$especialidad_usuario = isset($especialidades_map[$varsesion2]) ? $especialidades_map[$varsesion2] : '';
}

// Modificar la consulta según el tipo de usuario
$query_base = "SELECT 
    persona.*, 
    YEAR(CURDATE())-YEAR(persona.fec_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(persona.fec_nac,'%m-%d'), 0, -1) as edad,
    tipo_doc.nombre as tipodoc,
    exploracion_fisica.pad,
    exploracion_fisica.pas,
    exploracion_fisica.spo2,
    exploracion_fisica.fc,
    exploracion_fisica.temp,
    exploracion_fisica.peso,
    exploracion_fisica.talla
    FROM persona 
    LEFT JOIN tipo_doc ON tipo_doc.id=persona.id_tipodoc 
    LEFT JOIN exploracion_fisica ON exploracion_fisica.id_historia=persona.id
    WHERE persona.id_tipopersona='2' AND persona.status = 1";

// Agregar filtro por especialidad si no es Superadmin
if ($varsesion != 'Superadmin' && !empty($especialidad_usuario)) {
	$query_base .= " AND persona.especialidad = '".$especialidad_usuario."'";
}

$query_base .= " ORDER BY persona.id DESC";

$resultado = $conexion->query($query_base);

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Consultorios Medicos Especializados Luz de Esperanza</title>
	<meta name="description" content="Consultorio Medicos Especializados Luz de Esperanza">
	<link rel="stylesheet" href="css/bootstrap.css">
	<link rel="stylesheet" href="css/estilos.css">
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
</head>

<body>
	<?php include "./layouts/header.php" ?>

	<section>
		<?php
		if (isset($_GET['error'])) {
		?>
			<div class="alert alert-danger" role="alert">
				<?php echo $_GET['error']; ?>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
		<?php
		}
		?>

		<?php

		?>
		<br><br>
		<div class="container-fluid">

			<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar..." style="width: 400px;"> &nbsp; &nbsp;
			<?php if ($varsesion != 'Admin') { ?>
				<a href=""
					data-toggle="modal" data-target="#exampleModal"><img src="img/cliente.jpg" alt="" id="icono"></a>
			<?php } ?>
			<br><br>
			<table id="myTable">
				<tr class="header">
					<th style="width:60%;">Datos del Paciente</th>
					<th style="width:40%;">Acciones</th>
				</tr>
				<?php
				while ($mostrar = mysqli_fetch_assoc($resultado)) {
				?>
					<tr>
						<td>
							<span id="nombre"><?php echo $mostrar['ape1'] ?> <?php echo $mostrar['ape2'] ?>, <?php echo $mostrar['nombre'] ?> </span><br>
							<span id="datos" style="display: flex;font-size:medium">
								<span>
									Tipo Documento: <?php echo $mostrar['tipodoc'] ?><br>
									Nro. Doc: <?php echo $mostrar['nrodoc'] ?> <br>
									Fec. Nac: <?php echo ($mostrar['fec_nac'] && $mostrar['fec_nac'] != '0000-00-00') ? date('d-m-Y', strtotime($mostrar['fec_nac'])) : ''; ?>
									<?php echo ($mostrar['edad'] && $mostrar['edad'] > 0) ? '(Edad: ' . $mostrar['edad'] . ' años)' : ''; ?> <br>
									Estado Civil: <?php echo $mostrar['estado_civil'] ?> <br>
									Sexo: <?php echo $mostrar['sexo'] ?> <br>
									<b>Especialidad: <?php echo $mostrar['especialidad'] ?></b><br>
								</span>
								<span style="margin-left: 40px;">
									PAD: <?php echo ($mostrar['pad'] == 0) ? '0' : $mostrar['pad']; ?> mmHg<br>
									PAS: <?php echo ($mostrar['pas'] == 0) ? '0' : $mostrar['pas']; ?> mmHg<br>
									SpO2: <?php echo ($mostrar['spo2'] == 0) ? '0' : $mostrar['spo2']; ?> %<br>
									FC: <?php echo ($mostrar['fc'] == 0) ? '0' : $mostrar['fc']; ?> lpm<br>
									Temperatura: <?php echo ($mostrar['temp'] == 0) ? '0' : $mostrar['temp']; ?> °C<br>
									Peso: <?php echo ($mostrar['peso'] == 0) ? '0' : $mostrar['peso']; ?> kg<br>
									Talla: <?php echo ($mostrar['talla'] == 0) ? '0' : $mostrar['talla']; ?> cm<br>
								</span>
							</span>
						</td>
						<td>
							<?php if ($varsesion != 'Admin') { ?>
								<button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar"
									data-id="<?php echo $mostrar['id'] ?>"
									data-nombre="<?php echo $mostrar['nombre'] ?>"
									data-ape1="<?php echo $mostrar['ape1'] ?>"
									data-ape2="<?php echo $mostrar['ape2'] ?>"
									data-nrodoc="<?php echo $mostrar['nrodoc'] ?>"
									data-id_tipodoc="<?php echo $mostrar['id_tipodoc'] ?>"
									data-fec_nac="<?php echo $mostrar['fec_nac'] ?>"
									data-sexo="<?php echo $mostrar['sexo'] ?>"
									data-estado_civil="<?php echo $mostrar['estado_civil'] ?>"
									data-especialidad="<?php echo $mostrar['especialidad'] ?>"
									data-pad="<?php echo $mostrar['pad'] ?>"
									data-pas="<?php echo $mostrar['pas'] ?>"
									data-spo2="<?php echo $mostrar['spo2'] ?>"
									data-fc="<?php echo $mostrar['fc'] ?>"
									data-temp="<?php echo $mostrar['temp'] ?>"
									data-peso="<?php echo $mostrar['peso'] ?>"
									data-talla="<?php echo $mostrar['talla'] ?>">
									<i class="fa fa-pencil" aria-hidden="true"></i>
								</button>
							<?php } ?>
							<?php if ($varsesion != 'Admin') { ?>
								<button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar"
									data-id="<?php echo $mostrar['id']; ?>">
									<i class="fa fa-trash" aria-hidden="true"></i>
								</button>
							<?php } ?>
							<a href="historia.php?id=<?php echo $mostrar['id'] ?>">
								<button class="btn btn-warning btn-small">
									<i class="fa fa-medkit" aria-hidden="true"></i>
								</button>
							</a>
							<?php if ($varsesion != 'Admin') { ?>
								<a href="pago.php?id=<?php echo $mostrar['id'] ?>" style="text-decoration: none;">
									<button class="btn btn-success btn-small">
										<i class="fa fa-money" aria-hidden="true"></i>
									</button>
								</a>
							<?php } ?>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
		</div>
	</section>

	<!-- Modal Ingresar -->
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="formIngresar" method="post">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Ingresar Paciente</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div>
							<label for="nombre">Nombre <span style="color: red">*</span></label>
							<input type="text" name="nombre" id="nombre" placeholder="Ingrese el nombre del paciente" class="form-control" required>
						</div>
						<br>
						<div>
							<label for="ape1">Apellido Paterno <span style="color: red">*</span></label>
							<input type="text" name="ape1" id="ape1" placeholder="Ingrese el apellido paterno" class="form-control" required>
						</div>
						<br>
						<div>
							<label for="ape2">Apellido Materno</label>
							<input type="text" name="ape2" id="ape2" placeholder="Ingrese el apellido materno" class="form-control">
						</div>
						<br>
						<div>
							<label for="id_tipodoc">Tipo Documento</label>
							<select name="id_tipodoc" id="id_tipodoc" class="form-control" onchange="actualizarValidacionDocumento(this, 'nrodoc')">
								<?php
								$resultado = $conexion->query("select * from tipo_doc");
								while ($f = mysqli_fetch_array($resultado)) {
								?>
									<option value="<?php echo $f['id'] ?>"><?php echo $f['nombre'] ?></option>
								<?php } ?>
							</select>
						</div>
						<br>
						<div>
							<label for="nrodoc">Nro. Documento</label>
							<input type="text" name="nrodoc" id="nrodoc" placeholder="Ingrese el Nro. Documento" class="form-control" minlength="8" maxlength="8" pattern="[0-9]{8}">
						</div>
						<br>
						<div>
							<label for="fec_nac">Fecha de Nacimiento</label>
							<input type="date" name="fec_nac" id="fec_nac" class="form-control">
						</div>
						<br>
						<div>
							<label for="sexo">Sexo</label>
							<select name="sexo" id="sexo" class="form-control">
								<option value="Masculino">Masculino</option>
								<option value="Femenino">Femenino</option>
							</select>
						</div>
						<br>
						<div>
							<label for="especialidad">Especialidad</label>
							<select name="especialidad" id="especialidad" class="form-control">
								<?php
								$resultado = $conexion->query("SELECT DISTINCT especialidad FROM persona WHERE especialidad IS NOT NULL AND especialidad != '' ORDER BY especialidad ASC");
								while ($f = mysqli_fetch_array($resultado)) {
								?>
									<option value="<?php echo $f['especialidad'] ?>"><?php echo $f['especialidad'] ?></option>
								<?php } ?>
							</select>
						</div>
						<br>
						<div>
							<label for="estado_civil">Estado Civil</label>
							<select name="estado_civil" id="estado_civil" class="form-control">
								<option value="Soltero">Soltero</option>
								<option value="Casado">Casado</option>
								<option value="Divorciado">Divorciado</option>
							</select>
						</div>
						<input type="hidden" name="id_tipopersona" value="2">

						<br>
						<span style="font-weight: bold;">SIGNOS VITALES:</span>
						<br>
						<div>
							<label for="pad">Presión Arterial Diastólica (PAD)</label>
							<input type="number" step=".01" id="pad" name="pad" max="300" class="form-control" placeholder="Ingrese la presión arterial">
						</div>
						<br>
						<div>
							<label for="pas">Presión Arterial Sistólica (PAS)</label>
							<input type="number" step=".01" id="pas" name="pas" max="300" class="form-control" placeholder="Ingrese la presión arterial">
						</div>
						<br>
						<div>
							<label for="spo2">Saturación de Oxígeno (SpO2)</label>
							<input type="number" step=".01" id="spo2" name="spo2" max="300" class="form-control" placeholder="Ingrese la saturación de oxígeno">
						</div>
						<br>
						<div>
							<label for="fc">Frecuencia Cardíaca (FC)</label>
							<input type="number" step=".01" id="fc" name="fc" max="300" class="form-control" placeholder="Ingrese la frecuencia cardíaca">
						</div>
						<br>
						<div>
							<label for="temp">Temperatura</label>
							<input type="number" step=".01" id="temp" name="temp" max="300.0" class="form-control" placeholder="Ingrese la temperatura">
						</div>
						<br>
						<div>
							<label for="peso">Peso (kg)</label>
							<input type="number" step=".01" id="peso" name="peso" max="300.0" class="form-control" placeholder="Ingrese el peso">
						</div>
						<br>
						<div>
							<label for="talla">Talla (cm)</label>
							<input type="number" step=".01" id="talla" name="talla" max="300" class="form-control" placeholder="Ingrese la talla">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<!-- Modal Editar -->
	<div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form id="formEditar" method="post">
					<div class="modal-header">
						<h5 class="modal-title" id="modalEditarLabel">Editar paciente</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<div>
							<label for="nombreEdit">Nombre <span style="color: red">*</span></label>
							<input type="text" name="nombre" id="nombreEdit" placeholder="Nombre" class="form-control" required empty>
							<input type="hidden" name="id" id="idEdit" class="form-control">
						</div>
						<br>
						<div>
							<label for="ape1Edit">Apellido Paterno <span style="color: red">*</span></label>
							<input type="text" name="ape1" id="ape1Edit" placeholder="Apellido Paterno" class="form-control" required empty>
						</div>
						<br>
						<div>
							<label for="ape2Edit">Apellido Materno</label>
							<input type="text" name="ape2" id="ape2Edit" placeholder="Apellido Materno" class="form-control" empty>
						</div>
						<br>
						<div>
							<label for="id_tipodocEdit">Tipo de Documento <span style="color: red">*</span></label>
							<select name="id_tipodoc" id="id_tipodocEdit" class="form-control" required onchange="actualizarValidacionDocumento(this, 'nrodocEdit')">
								<option value="1" selected>DNI</option>
								<option value="2">Carnet de Extranjería</option>
							</select>
						</div>
						<br>
						<div>
							<label for="nrodocEdit">Nro. Documento</label>
							<input type="text" name="nrodoc" id="nrodocEdit" placeholder="Nro. Documento" class="form-control" minlength="8" maxlength="8" pattern="[0-9]{8}">
						</div>
						<br>
						<div>
							<label for="fec_nacEdit">Fecha de Nacimiento</label>
							<input type="date" name="fec_nac" id="fec_nacEdit" class="form-control" empty>
						</div>
						<br>
						<div>
							<label for="sexoEdit">Sexo</label>
							<select name="sexo" id="sexoEdit" class="form-control" empty>
								<option value="Masculino">Masculino</option>
								<option value="Femenino">Femenino</option>
							</select>
						</div>
						<br>
						<div>
							<label for="especialidadEdit">Especialidad</label>
							<select name="especialidad" id="especialidadEdit" class="form-control">
								<?php
								$resultado = $conexion->query("SELECT DISTINCT especialidad FROM persona WHERE especialidad IS NOT NULL AND especialidad != '' ORDER BY especialidad ASC");
								while ($f = mysqli_fetch_array($resultado)) {
								?>
									<option value="<?php echo $f['especialidad'] ?>"><?php echo $f['especialidad'] ?></option>
								<?php } ?>
							</select>
						</div>
						<br>
						<div>
							<label for="estado_civilEdit">Estado Civil</label>
							<select name="estado_civil" id="estado_civilEdit" class="form-control" empty>
								<option value="Soltero">Soltero</option>
								<option value="Casado">Casado</option>
								<option value="Divorciado">Divorciado</option>
							</select>
						</div>
						<input type="hidden" name="id_tipopersona" value="2">

						<br>
						<span style="font-weight: bold;">SIGNOS VITALES:</span>
						<br>
						<div>
							<label for="padEdit">Presión Arterial Diastólica (PAD)</label>
							<input type="number" step=".01" id="padEdit" name="pad" max="300" class="form-control" placeholder="Ingrese la presión arterial">
						</div>
						<br>
						<div>
							<label for="pasEdit">Presión Arterial Sistólica (PAS)</label>
							<input type="number" step=".01" id="pasEdit" name="pas" max="300" class="form-control" placeholder="Ingrese la presión arterial">
						</div>
						<br>
						<div>
							<label for="spo2Edit">Saturación de Oxígeno (SpO2)</label>
							<input type="number" step=".01" id="spo2Edit" name="spo2" max="300" class="form-control" placeholder="Ingrese la saturación de oxígeno" empty>
						</div>
						<br>
						<div>
							<label for="fcEdit">Frecuencia Cardíaca (FC)</label>
							<input type="number" step=".01" id="fcEdit" name="fc" max="300" class="form-control" placeholder="Ingrese la frecuencia cardíaca" empty>
						</div>
						<br>
						<div>
							<label for="tempEdit">Temperatura</label>
							<input type="number" step=".01" id="tempEdit" name="temp" max="300.0" class="form-control" placeholder="Ingrese la temperatura" empty>
						</div>
						<br>
						<div>
							<label for="pesoEdit">Peso (kg)</label>
							<input type="number" step=".01" id="pesoEdit" name="peso" max="300.0" class="form-control" placeholder="Ingrese el peso" empty>
						</div>
						<br>
						<div>
							<label for="tallaEdit">Talla (cm)</label>
							<input type="number" step=".01" id="tallaEdit" name="talla" max="300" class="form-control" placeholder="Ingrese la talla" empty>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
						<button type="submit" class="btn btn-primary">Guardar</button>
					</div>
				</form>
			</div>
		</div>
	</div>


	<!-- Modal Eliminar-->
	<div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="modalEliminarLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEliminarLabel">Eliminar paciente</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					¿Deseas eliminar el paciente?
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
					<button type="submit" class="btn btn-danger eliminarFila" data-dismiss="modal">Eliminar</button>
				</div>
			</div>
		</div>
	</div>

	<script src="js/jquery-3.5.1.min.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.js"></script>
	<script>
		// Función para actualizar la validación del documento según el tipo
		function actualizarValidacionDocumento(selectElement, inputId) {
			const nroDocInput = document.getElementById(inputId);
			if (selectElement.value === "2") { // Si es Carnet de Extranjería
				nroDocInput.maxLength = "20";
				nroDocInput.minLength = "0";
				nroDocInput.pattern = ".*";
			} else { // Para DNI y otros
				nroDocInput.maxLength = "8";
				nroDocInput.minLength = "8";
				nroDocInput.pattern = "[0-9]{8}";
			}
		}

		$(document).ready(function() {
			var ultimaActualizacion = 0;
			var ultimosClientes = [];
			var especialidadUsuario = '<?php echo $especialidad_usuario; ?>';
			var esAdmin = '<?php echo $varsesion; ?>' === 'Superadmin';

			// Manejar el envío del formulario de ingreso
			$("#formIngresar").on('submit', function(e) {
				e.preventDefault();

				$.ajax({
					url: 'php/ingresarcliente.php',
					method: 'POST',
					data: $(this).serialize(),
					dataType: 'json'
				}).done(function(response) {
					if (response.success) {
						$('#exampleModal').modal('hide');
						$("#formIngresar")[0].reset();
						actualizarTablaClientes(); // Actualizar inmediatamente después de insertar
					} else {
						alert('Error al ingresar el cliente: ' + response.message);
					}
				}).fail(function(xhr) {
					console.error('Error:', xhr.responseText);
					alert('Error al procesar la solicitud. Por favor, inténtelo de nuevo.');
				});
			});

			// Manejar el envío del formulario de edición
			$("#formEditar").on('submit', function(e) {
				e.preventDefault();
				var formData = $(this).serialize();

				$.ajax({
					url: 'php/editarcliente.php',
					method: 'POST',
					data: formData,
					dataType: 'json'
				}).done(function(response) {
					if (response.success) {
						$('#modalEditar').modal('hide');
						// Forzar actualización inmediata
						$.ajax({
							url: 'php/obtener_clientes.php',
							method: 'GET',
							dataType: 'json',
							cache: false
						}).done(function(response) {
							actualizarTablaHTML(response);
						});
					} else {
						alert('Error al actualizar el cliente: ' + response.message);
					}
				}).fail(function(xhr) {
					console.error('Error:', xhr.responseText);
					alert('Error al procesar la solicitud. Por favor, inténtelo de nuevo.');
				});
			});

			// Función para actualizar el HTML de la tabla
			function actualizarTablaHTML(response) {
				var tablaHTML = `
					<tr class="header">
						<th style="width:60%;">Datos del Paciente</th>
						<th style="width:40%;">Acciones</th>
					</tr>
				`;

				response.forEach(function(cliente) {
					// Aplicar filtro de especialidad
					if (!esAdmin && cliente.especialidad !== especialidadUsuario) {
						return;
					}

					tablaHTML += `
						<tr>
							<td>
								<span id="nombre">${cliente.ape1} ${cliente.ape2 || ''}, ${cliente.nombre}</span><br>
								<span id="datos" style="display: flex;font-size:medium">
									<span>
										Tipo Documento: ${cliente.tipodoc}<br>
										Nro. Doc: ${cliente.nrodoc || ''}<br>
										Fec. Nac: ${(cliente.fec_nac && cliente.fec_nac != '0000-00-00') ? formatDate(cliente.fec_nac) : ''} 
										${(cliente.edad && cliente.edad > 0) ? '(Edad: ' + cliente.edad + ' años)' : ''}<br>
										Estado Civil: ${cliente.estado_civil || ''}<br>
										Sexo: ${cliente.sexo || ''}<br>
										<b>Especialidad: ${cliente.especialidad}</b><br>
									</span>
									<span style="margin-left: 40px;">
										PAD: ${cliente.pad || '0'} mmHg<br>
										PAS: ${cliente.pas || '0'} mmHg<br>
										SpO2: ${cliente.spo2 || '0'} %<br>
										FC: ${cliente.fc || '0'} lpm<br>
										Temperatura: ${cliente.temp || '0'} °C<br>
										Peso: ${cliente.peso || '0'} kg<br>
										Talla: ${cliente.talla || '0'} cm<br>
									</span>
								</span>
							</td>
							<td>
								<?php if ($varsesion != 'Admin') { ?>
								<button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" 
									data-id="${cliente.id}" 
									data-nombre="${cliente.nombre}" 
									data-ape1="${cliente.ape1}" 
									data-ape2="${cliente.ape2 || ''}" 
									data-nrodoc="${cliente.nrodoc || ''}" 
									data-id_tipodoc="${cliente.id_tipodoc}" 
									data-fec_nac="${cliente.fec_nac || ''}"
									data-sexo="${cliente.sexo || ''}"
									data-especialidad="${cliente.especialidad}"
									data-estado_civil="${cliente.estado_civil || ''}"
									data-pad="${cliente.pad || ''}"
									data-pas="${cliente.pas || ''}"
									data-spo2="${cliente.spo2 || ''}"
									data-fc="${cliente.fc || ''}"
									data-temp="${cliente.temp || ''}"
									data-peso="${cliente.peso || ''}"
									data-talla="${cliente.talla || ''}">
									<i class="fa fa-pencil" aria-hidden="true"></i>
								</button>
								<?php } ?>
								<?php if ($varsesion != 'Admin') { ?>
								<button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" 
									data-id="${cliente.id}">
									<i class="fa fa-trash" aria-hidden="true"></i>
								</button>
								<?php } ?>
								<a href="historia.php?id=${cliente.id}">
									<button class="btn btn-warning btn-small">
										<i class="fa fa-medkit" aria-hidden="true"></i>
									</button>
								</a>
								<?php if ($varsesion != 'Admin') { ?>
								<a href="pago.php?id=${cliente.id}" style="text-decoration: none;">
									<button class="btn btn-success btn-small">
										<i class="fa fa-money" aria-hidden="true"></i>
									</button>
								</a>
								<?php } ?>
							</td>
						</tr>
					`;
				});

				$("#myTable").html(tablaHTML);
				activarEventosBotones();
			}

			// Función para actualizar la tabla de clientes
			function actualizarTablaClientes(forzarActualizacion = false) {
				const ahora = new Date().getTime();
				if (!forzarActualizacion && ahora - ultimaActualizacion < 500) return;
				
				ultimaActualizacion = ahora;
				
				$.ajax({
					url: 'php/obtener_clientes.php',
					method: 'GET',
					dataType: 'json',
					cache: false
				}).done(function(response) {
					if (!forzarActualizacion && JSON.stringify(response) === JSON.stringify(ultimosClientes)) {
						return;
					}
					
					ultimosClientes = response;
					actualizarTablaHTML(response);
				});
			}

			// Función para formatear fecha
			function formatDate(dateString) {
				if (!dateString) return '';
				var parts = dateString.split('-');
				return parts[2] + '-' + parts[1] + '-' + parts[0];
			}

			// Función para activar eventos de los botones
			function activarEventosBotones() {
				$(".btnEliminar").click(function() {
					idEliminar = $(this).data('id');
				});

				$(".eliminarFila").click(function() {
					$.ajax({
						url: 'php/eliminarcliente.php',
						method: 'POST',
						data: {
							id: idEliminar
						},
						dataType: 'json'
					}).done(function(response) {
						if (response.success) {
							$('#modalEliminar').modal('hide');
							actualizarTablaClientes();
						} else {
							alert('Error al desactivar el cliente: ' + response.message);
						}
					}).fail(function(xhr) {
						console.error('Error:', xhr.responseText);
						alert('Error al procesar la solicitud. Por favor, inténtelo de nuevo.');
					});
				});

				$(".btnEditar").click(function() {
					var id = $(this).data('id');
					$("#idEdit").val(id);
					$("#nombreEdit").val($(this).data('nombre'));
					$("#ape1Edit").val($(this).data('ape1'));
					$("#ape2Edit").val($(this).data('ape2'));
					$("#nrodocEdit").val($(this).data('nrodoc'));
					$("#id_tipodocEdit").val($(this).data('id_tipodoc') || '1');
					$("#fec_nacEdit").val($(this).data('fec_nac'));
					$("#sexoEdit").val($(this).data('sexo'));
					$("#especialidadEdit").val($(this).data('especialidad'));
					$("#estado_civilEdit").val($(this).data('estado_civil'));
					
					// Asignar valores de signos vitales sin valores por defecto
					$("#padEdit").val($(this).data('pad'));
					$("#pasEdit").val($(this).data('pas'));
					$("#spo2Edit").val($(this).data('spo2'));
					$("#fcEdit").val($(this).data('fc'));
					$("#tempEdit").val($(this).data('temp'));
					$("#pesoEdit").val($(this).data('peso'));
					$("#tallaEdit").val($(this).data('talla'));

					// Actualizar la validación del documento
					actualizarValidacionDocumento(document.getElementById('id_tipodocEdit'), 'nrodocEdit');
				});

				// Remover el evento de limpieza del modal de edición
				$('#modalEditar').off('hidden.bs.modal');
			}

			var idEliminar = -1;

			// Activar eventos iniciales
			activarEventosBotones();

			// Primera actualización al cargar la página
			actualizarTablaClientes(true);

			// Actualización automática cada 2 segundos
			setInterval(actualizarTablaClientes, 2000);

			// Limpiar formulario cuando se cierra el modal
			$('#exampleModal').on('hidden.bs.modal', function() {
				$("#formIngresar")[0].reset();
			});

			// Limpiar formulario cuando se cierra el modal de edición
			$('#modalEditar').on('hidden.bs.modal', function() {
				$("#formEditar")[0].reset();
			});

			function myFunction() {
				var input, filter, table, tr, td, i, txtValue;
				input = document.getElementById("myInput");
				filter = input.value.toUpperCase();
				table = document.getElementById("myTable");
				tr = table.getElementsByTagName("tr");

				for (i = 0; i < tr.length; i++) {
					td = tr[i].getElementsByTagName("td")[0];
					if (td) {
						txtValue = td.textContent || td.innerText;
						if (txtValue.toUpperCase().indexOf(filter) > -1) {
							tr[i].style.display = "";
						} else {
							tr[i].style.display = "none";
						}
					}
				}
			}
		});
	</script>
</body>

</html>