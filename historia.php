<?php 
include "php/conexion.php";
date_default_timezone_set('America/Lima');
 session_start();
 error_reporting(0);
 $varsesion = $_SESSION['acceso'];
 $varsesion2 = $_SESSION['user'];

if ($varsesion2 == null || $varsesion2 = '') {
    header("Location: logueo.php");
    die();
  }
  
$resultado = $conexion->query("select persona.*, YEAR(CURDATE())-YEAR(persona.fec_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(persona.fec_nac,'%m-%d'), 0 , -1 ) as edad, 
tipo_doc.nombre as tipodoc, exploracion_fisica.pad as pad, exploracion_fisica.pas as pas, exploracion_fisica.spo2 as spo2, 
exploracion_fisica.fc as fc, exploracion_fisica.temp as temp, exploracion_fisica.peso as peso, exploracion_fisica.talla as talla from persona 
left join exploracion_fisica on exploracion_fisica.id_historia=persona.id 
left join tipo_doc on tipo_doc.id=persona.id_tipodoc where persona.id='" . $_REQUEST['id'] . "' ORDER BY ape1, ape2, nombre ASC");
$mostrar = mysqli_fetch_assoc($resultado);

$resultado2 = $conexion->query("select historia.* from historia where id_cliente=" . $_REQUEST['id'] . " ORDER BY id DESC");
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
          if (isset($_GET['success'])) {
         ?>
        <div class="alert alert-success" role="alert">
          <?php echo $_GET['success']; ?>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php 
          }
         ?>
		<br>
		<div class="container-fluid">
			<div class="row">
        <div class="col-12 col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
          <span style="font-weight: bold; font-size: 25px;">
            <?php echo $mostrar['ape1'] ?> <?php echo $mostrar['ape2'] ?>, <?php echo $mostrar['nombre'] ?>
          </span>
          <br><br>
          <span style="font-size: 18px;">
                Tipo Doc: <?php echo $mostrar['tipodoc'] ?> <br>
                Nro. Doc: <?php echo $mostrar['nrodoc'] ?> <br>
                Teléfono: <?php echo $mostrar['telef'] ?> <br>
                Correo: <?php echo $mostrar['correo'] ?> <br>
                Fec. Nac: <?php echo ($mostrar['fec_nac'] && $mostrar['fec_nac'] != '0000-00-00') ? date('d-m-Y', strtotime($mostrar['fec_nac'])) : ''; ?> 
                <?php echo ($mostrar['edad'] && $mostrar['edad'] > 0) ? '(Edad: ' . $mostrar['edad'] . ' años)' : ''; ?> <br>
                Sexo: <?php echo $mostrar['sexo'] ?> <br>
                Estado Civil: <?php echo $mostrar['estado_civil'] ?> <br>
                <br><h4>SIGNOS VITALES</h4>
                PAD: <?php echo $mostrar['pad'] ?> mmHg<br>
                PAS: <?php echo $mostrar['pas'] ?> mmHg<br>
                SpO2: <?php echo $mostrar['spo2'] ?> %<br>
                FC: <?php echo $mostrar['fc'] ?> lpm<br>
                Temperatura: <?php echo $mostrar['temp'] ?> °C<br>
                Peso: <?php echo $mostrar['peso'] ?> kg<br>
                Talla: <?php echo $mostrar['talla'] ?> cm<br>
          </span>
          <br><br><br><br>
        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar..."> &nbsp; &nbsp; 
          <?php if ($varsesion == 'Admin' || $varsesion == 'Superadmin') { ?> 
            <button class="btn btn-success btn-small" data-toggle="modal" data-target="#exampleModal">
              <i class="fa fa-medkit" aria-hidden="true"></i>
            </button>
          <?php } ?>
          <br><br>

          <table id="myTable">
          <?php 
            while ($mostrar2 = mysqli_fetch_assoc($resultado2)) {
              $resultado3 = $conexion->query("select exploracion_fisica.* from exploracion_fisica where id_historia=" . $mostrar2['id']);
              $mostrar3 = mysqli_fetch_array($resultado3);

              $resultadoDoctor = $conexion->query("select persona.id as id, persona.nombre as nombre, persona.ape1 as ape1, 
              persona.ape2 as ape2 from historia LEFT join persona on historia.id_doctor=persona.id where historia.id=" . $mostrar2['id']);
              $mostrarDoctor = mysqli_fetch_array($resultadoDoctor);
            ?>


              <tr>
                <td>

                  Nro. Historia: <?php echo $mostrar2['id']; ?> <br>
                  Fecha: <?php echo date('d-m-Y', strtotime($mostrar2['fecha'])); ?> <br><br>
                  <b>Doctor:</b> <?php echo $mostrarDoctor['nombre']; ?> <?php echo $mostrarDoctor['ape1']; ?> <?php echo $mostrarDoctor['ape2'];?> <br><br>
                  Motivo: <?php echo $mostrar2['motivo']; ?> <br><br>
                  Enfermedad Actual: <?php echo $mostrar2['enfermedad_actual']; ?> <br><br>
                  Antecedentes Familiares: <?php echo $mostrar2['antec_familiar']; ?> <br><br>
                  Antecedentes Personales: <?php echo $mostrar2['antec_personales']; ?> <br><br>
                  Examen Físico: <?php echo $mostrar2['exam_fisico']; ?> <br><br>
                  Diagnóstico Presuntivo: <?php echo $mostrar2['diag_presuntivo']; ?> <br><br>
                  Exámenes Auxiliares: <?php echo $mostrar2['exam_auxiliar']; ?> <br><br>
                  Laboratorio: <?php echo $mostrar2['laboratorio']; ?> <br><br>
                  Otros: <?php echo $mostrar2['otros']; ?> <br><br>
                  Diagnóstico Definitivo: <?php echo $mostrar2['diag_definitivo']; ?> <br><br>
                  Tratamientos: <?php echo $mostrar2['tratamiento']; ?> <br><br>
                  <?php if ($varsesion == 'Admin' || $varsesion == 'Superadmin') { ?>
                    <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar"
                      data-id_cliente="<?php echo $_REQUEST['id'] ?>" data-id="<?php echo $mostrar2['id'] ?>"
                      data-id_doctor="<?php echo $mostrarDoctor['id'] ?>" data-fecha="<?php echo $mostrar2['fecha'] ?>"
                      data-motivo="<?php echo $mostrar2['motivo'] ?>" data-antec_familiar="<?php echo $mostrar2['antec_familiar'] ?>"
                      data-antec_personales="<?php echo $mostrar2['antec_personales'] ?>" data-exam_fisico="<?php echo $mostrar2['exam_fisico'] ?>"
                      data-diag_presuntivo="<?php echo $mostrar2['diag_presuntivo'] ?>" data-diag_definitivo="<?php echo $mostrar2['diag_definitivo'] ?>"
                      data-exam_auxiliar="<?php echo $mostrar2['exam_auxiliar'] ?>" data-laboratorio="<?php echo $mostrar2['laboratorio'] ?>"
                      data-otros="<?php echo $mostrar2['otros'] ?>" data-diag_definitivo="<?php echo $mostrar2['diag_definitivo'] ?>"
                      data-tratamientos="<?php echo $mostrar2['tratamiento'] ?>">Editar</button>
                  <?php } ?>
                  <?php if ($varsesion == 'Admin' || $varsesion == 'Superadmin') { ?>
                    <button class="btn btn-danger btn-small btnEliminar" data-id="<?php echo $mostrar2['id']; ?>" 
                      data-toggle="modal" data-target="#modalEliminar">Eliminar
                    </button>
                  <?php } ?>
                </td>
              </tr>
           <?php } ?>
            </table>
        </div>
      </div>
		</div>
	</section>

<!-- Modal Ingresar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="php/ingresarhistoria.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ingresar historia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
         		<label for="fecha">Fecha</label>
              <input type="date" name="fecha" id="fecha" class="form-control" value="<?php echo date('Y-m-d'); ?>">
            <input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $_REQUEST['id'] ?>">       		
        	</div>
        	<br>
          <div>
            <label for="id_doctor">Doctor</label>
            <select name="id_doctor" id="id_doctor" class="form-control">
              <?php 
                $resultadoDoctor2 = $conexion->query("select persona.id as id, persona.nombre as nombre, persona.ape1 as ape1, 
                persona.ape2 as ape2 from persona where id_tipopersona='1' order by persona.nombre asc");
                while ($mostrarDoctor2 = mysqli_fetch_array($resultadoDoctor2)) {
               ?>
               <option value="<?php echo $mostrarDoctor2['id'] ?>"><?php echo $mostrarDoctor2['nombre'] ?> <?php echo $mostrarDoctor2['ape1'] ?> <?php echo $mostrarDoctor2['ape2'] ?></option>
              <?php } ?>
            </select>         
          </div>
          <br>            
        	<div>
              <label for="motivo">Motivo de la consulta <span style="color: red">*</span></label>
              <textarea name="motivo" id="motivo" cols="30" rows="5" class="form-control" required></textarea>
        	</div>
        	<br>
        	<div>
              <label for="antec_familiar">Antecedentes Heredofamiliares</label>
              <textarea name="antec_familiar" id="antec_familiar" cols="30" rows="5" class="form-control"></textarea>
        	</div>
          <br>
          <div>
              <label for="antec_personales">Antecedentes Personales</label>
              <textarea name="antec_personales" id="antec_personales" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="exam_fisico">Examen Físico</label>
              <textarea name="exam_fisico" id="exam_fisico" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="diag_presuntivo">Diagnóstico Presuntivo</label>
              <textarea name="diag_presuntivo" id="diag_presuntivo" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="exam_auxiliar">Exámenes Auxiliares</label>
              <textarea name="exam_auxiliar" id="exam_auxiliar" cols="30" rows="5" class="form-control"></textarea>
          <br>
          <div>
                <label for="laboratorio">Laboratorio</label>
                <textarea name="laboratorio" id="laboratorio" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
                <label for="otros">Otros</label>
                <textarea name="otros" id="otros" cols="30" rows="5" class="form-control"></textarea>
          </div>
          </div>
          <br>
          <div>
              <label for="diag_definitivo">Diagnóstico Definitivo</label>
              <textarea name="diag_definitivo" id="diag_definitivo" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="tratamiento">Tratamientos</label>
              <textarea name="tratamiento" id="tratamiento" cols="30" rows="5" class="form-control"></textarea>
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
        <h5 class="modal-title" id="modalEditarLabel">Editar historia</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
            <label for="fechaEdit">Fecha</label>
              <input type="date" name="fecha" id="fechaEdit" class="form-control" value="<?php echo date('Y-m-d'); ?>">
            <input type="hidden" id="idEdit" name="id">   
            <input type="hidden" id="id_clienteEdit" name="id_cliente">          
          </div>
          <br>
          <div>
            <label for="id_doctor">Doctor</label>
            <select name="id_doctor" id="id_doctorEdit" class="form-control">
              <?php 
                $resultadoDoctor2 = $conexion->query("select persona.id as id, persona.nombre as nombre, persona.ape1 as ape1, 
                persona.ape2 as ape2 from persona where id_tipopersona='1' order by persona.nombre asc");
                while ($mostrarDoctor2 = mysqli_fetch_array($resultadoDoctor2)) {
               ?>
               <option value="<?php echo $mostrarDoctor2['id'] ?>"><?php echo $mostrarDoctor2['nombre'] ?> <?php echo $mostrarDoctor2['ape1'] ?> 
               <?php echo $mostrarDoctor2['ape2'] ?></option>
              <?php } ?>
            </select>         
          </div>
          <br>
          <div>
              <label for="motivoEdit">Motivo de la consulta <span style="color: red">*</span></label>
              <textarea name="motivo" id="motivoEdit" cols="30" rows="5" class="form-control" required></textarea>
          </div>
          <br>
          <div>
              <label for="enfermedad_actualEdit">Enfermedad Actual</label>
              <textarea name="enfermedad_actual" id="enfermedad_actualEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="antec_familiarEdit">Antecedentes Familiares</label>
            <textarea name="antec_familiar" id="antec_familiarEdit" cols="30" rows="5" class="form-control"></textarea> 
          </div>
          <br>
          <div>
              <label for="antec_personalesEdit">Antecedentes Personales</label>
              <textarea name="antec_personales" id="antec_personalesEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="exam_fisicoEdit">Examen Físico</label>
              <textarea name="exam_fisico" id="exam_fisicoEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="diag_presuntivoEdit">Diagnóstico Presuntivo</label>
              <textarea name="diag_presuntivo" id="diag_presuntivoEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="exam_auxiliarEdit">Exámenes Auxiliares</label>
              <textarea name="exam_auxiliar" id="exam_auxiliarEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="laboratorioEdit">Laboratorio</label>
              <textarea name="laboratorio" id="laboratorioEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="otrosEdit">Otros</label>
              <textarea name="otros" id="otrosEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="diag_definitivoEdit">Diagnóstico Definitivo</label>
              <textarea name="diag_definitivo" id="diag_definitivoEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
              <label for="tratamientoEdit">Tratamientos</label>
              <textarea name="tratamiento" id="tratamientoEdit" cols="30" rows="5" class="form-control"></textarea>
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
        <h5 class="modal-title" id="modalEliminarLabel">Eliminar Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Deseas eliminar la historia?      
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
    $(document).ready(function() {
      // Establecer fecha actual por defecto en el formulario de ingreso y edición
      function setDefaultDate() {
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0');
        var yyyy = today.getFullYear();
        today = yyyy + '-' + mm + '-' + dd;
        $('#fecha, #fechaEdit').val(today);
      }

      // Establecer fecha al cargar la página
      setDefaultDate();

      // Establecer fecha al abrir los modales
      $('#exampleModal, #modalEditar').on('show.bs.modal', function() {
        setDefaultDate();
      });

      var idEliminar = -1;
      var idEditar = -1;
	    var fila;

      $(".btnEliminar").click(function() {
	      idEliminar = $(this).data('id');
	    });

      $(".eliminarFila").click(function() {
	     $.ajax({
	      url: 'php/eliminarhistoria.php',
	      method: 'POST',
          data: {
            id: idEliminar
	      }
        }).done(function(response) {
          location.reload();
	     });
	    });

      $(".btnEditar").click(function() {
	      var id = $(this).data('id');
        var id_cliente = $(this).data('id_cliente');
        $("#idEdit").val(id);
        $("#id_clienteEdit").val(id_cliente);
        $("#fechaEdit").val($(this).data('fecha'));
        $("#id_doctorEdit").val($(this).data('id_doctor'));
        $("#nombreEdit").val($(this).data('nombre'));
        $("#motivoEdit").val($(this).data('motivo'));
        $("#enfermedad_actualEdit").val($(this).data('enfermedad_actual'));
        $("#antec_familiarEdit").val($(this).data('antec_familiar'));
        $("#antec_personalesEdit").val($(this).data('antec_personales'));
        $("#exam_fisicoEdit").val($(this).data('exam_fisico'));
        $("#diag_presuntivoEdit").val($(this).data('diag_presuntivo'));
        $("#exam_auxiliarEdit").val($(this).data('exam_auxiliar'));
        $("#laboratorioEdit").val($(this).data('laboratorio'));
        $("#otrosEdit").val($(this).data('otros'));
        $("#diag_definitivoEdit").val($(this).data('diag_definitivo'));
        $("#tratamientoEdit").val($(this).data('tratamiento'));
      });

      $("#formEditar").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
          url: 'php/editarhistoria.php',
          method: 'POST',
          data: $(this).serialize(),
          dataType: 'json'
        }).done(function(response) {
          if (response.success) {
            $('#modalEditar').modal('hide');
            location.reload();
          } else {
            alert('Error: ' + response.message);
          }
        }).fail(function(xhr) {
          alert('Error al procesar la solicitud: ' + xhr.responseText);
        });
      });
	    });
	</script>

  <script>
    function myFunction() {
      // Declare variables
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable");
      tr = table.getElementsByTagName("tr");

      // Loop through all table rows, and hide those who don't match the search query
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
  </script>

    <script>
      $(document).ready(function() {
        // Función para actualizar la tabla de historias
        function actualizarTablaHistorias() {
          var id_cliente = <?php echo $_GET['id']; ?>;
          $.ajax({
            url: 'php/obtener_historias.php',
            method: 'GET',
          data: {
            id: id_cliente
          },
            dataType: 'json'
          }).done(function(response) {
          // Ordenar por id de historia
          response.sort(function(a, b) {
            return b.id - a.id; // Orden descendente
          });
          
            var tablaHTML = `
              <tr class="header">
                
              </tr>
            `;
            
            response.forEach(function(historia) {
              tablaHTML += `
                <tr>
                  <td>
                    Nro. Historia: ${historia.id} <br>
                    Fecha: ${historia.fecha.split('-').reverse().join('-')} <br><br>
                    <b>Doctor:</b> ${historia.doctor_nombre} ${historia.doctor_ape1} ${historia.doctor_ape2}<br><br>
                    Motivo: ${historia.motivo} <br><br>
                    Enfermedad Actual: ${historia.enfermedad_actual} <br><br>
                    Antecedentes Familiares: ${historia.antec_familiar} <br><br>
                    Antecedentes Personales: ${historia.antec_personales} <br><br>
                    Examen Físico: ${historia.exam_fisico} <br><br>
                    Diagnóstico Presuntivo: ${historia.diag_presuntivo} <br><br>
                    Exámenes Auxiliares: ${historia.exam_auxiliar} <br><br>
                    Laboratorio: ${historia.laboratorio} <br><br>
                    Otros: ${historia.otros} <br><br>
                    Diagnóstico Definitivo: ${historia.diag_definitivo} <br><br>
                    Tratamientos: ${historia.tratamiento} <br><br>
                    <?php if ($varsesion == 'Admin' || $varsesion == 'Superadmin') { ?>
                    <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" 
                      data-id_cliente="${historia.id_cliente}" data-id="${historia.id}"
                      data-id_doctor="${historia.id_doctor}" data-fecha="${historia.fecha}"
                      data-motivo="${historia.motivo}" data-antec_familiar="${historia.antec_familiar}"
                      data-antec_personales="${historia.antec_personales}" data-exam_fisico="${historia.exam_fisico}"
                      data-diag_presuntivo="${historia.diag_presuntivo}" data-exam_auxiliar="${historia.exam_auxiliar}"
                      data-laboratorio="${historia.laboratorio}" data-otros="${historia.otros}"
                      data-diag_definitivo="${historia.diag_definitivo}"
                      data-tratamiento="${historia.tratamiento}">
                      Editar
                    </button>
                    <button class="btn btn-danger btn-small btnEliminar" data-id="${historia.id}" data-toggle="modal" data-target="#modalEliminar">
                      Eliminar
                    </button>
                    <?php } ?>
                  </td>
                </tr>
              `;
            });
            
            $("#myTable").html(tablaHTML);
            activarEventosBotones();
          });
        }

        // Función para activar eventos de los botones
        function activarEventosBotones() {
          $(".btnEliminar").click(function() {
            idEliminar = $(this).data('id');
            fila = $(this).closest('tr');
          });

          $(".btnEditar").click(function() {
            var id = $(this).data('id');
            $("#idEdit").val(id);
          $("#motivoEdit").val($(this).data('motivo'));
          });
        }

        var idEliminar = -1;
        var fila;

        // Activar eventos iniciales
        activarEventosBotones();

      // Configurar actualización cada 5 segundos
      setInterval(actualizarTablaHistorias, 5000);

        // Eliminar historia
        $(".eliminarFila").click(function() {
          $.ajax({
            url: 'php/eliminarhistoria.php',
            method: 'POST',
            data: {
              id: idEliminar
            }
          }).done(function() {
            $(fila).fadeOut(500, function() {
              actualizarTablaHistorias();
            });
          });
        });
      });
  </script>
</body>

</html>