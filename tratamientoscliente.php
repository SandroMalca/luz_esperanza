<?php 
include "php/conexion.php";
date_default_timezone_set('America/Lima');
 session_start();
 error_reporting(0);
 $varsesion = $_SESSION['acceso'];
 $varsesion2 = $_SESSION['user'];

  if($varsesion2 == null || $varsesion2 = ''){
    header("Location: logueo.php");
    die();

  }

  
$resultado = $conexion -> query("select persona.*, YEAR(CURDATE())-YEAR(persona.fec_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(persona.fec_nac,'%m-%d'), 0 , -1 ) as edad, tipo_doc.nombre as tipodoc from persona LEFT JOIN tipo_doc on tipo_doc.id=persona.id_tipodoc LEFT JOIN historia on historia.id_cliente=persona.id where persona.id='".$_REQUEST['id']."' ORDER BY ape1, ape2, nombre ASC");
$mostrar=mysqli_fetch_assoc($resultado);

$resultado2 = $conexion -> query("select historia.* from historia where id_cliente=".$_REQUEST['id']." ORDER BY id DESC");
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
                Fec. Nac: <?php echo $mostrar['fec_nac'] ?> (<?php echo $mostrar['edad'] ?>) <br>
                Dirección: <?php echo $mostrar['direccion'] ?>
          </span>
          <br><br><br><br>
        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">
          <a href="historia.php?id=<?php echo $mostrar['id'] ?>" style="text-decoration: none;"><span class="btn-historia">Historias</span></a> &nbsp;&nbsp;&nbsp;&nbsp; <a href="tratamientoscliente.php?id=<?php echo $mostrar['id'] ?>" style="text-decoration: none;"><span class="btn-historia">Tratamientos</span></a> <br><br>

          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar..."> 
          
          <br><br>

          <?php 
            while ($mostrar2=mysqli_fetch_assoc($resultado2)) {

            $resultado3 = $conexion -> query("select tratamiento.* from tratamiento where id_historia=".$mostrar2['id']." ORDER BY id DESC");
            while($mostrar3=mysqli_fetch_assoc($resultado3)){
              $resultadoDoctor = $conexion -> query("select * from persona where id=".$mostrar3['cd']);
              $mostrarDoctor=mysqli_fetch_assoc($resultadoDoctor);
           ?>

            <table id="myTable">
            <tr><td>

           Fecha: <?php echo $mostrar3['fecha']; ?> <br><br>

           Tratamiento: <?php echo $mostrar3['tratamiento']; ?> <br>
           Doctor: <?php echo $mostrarDoctor['nombre']; ?> <?php echo $mostrarDoctor['ape1']; ?> <?php echo $mostrarDoctor['ape1']; ?><br>
           Próxima cita: <?php echo $mostrar3['prox_cita']; ?> <br><br>

           <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $mostrar3['id'] ?>" data-id_historia="<?php echo $mostrar2['id'] ?>" data-fecha="<?php echo $mostrar3['fecha'] ?>" data-tratamiento="<?php echo $mostrar3['tratamiento'] ?>" data-cd="<?php echo $mostrar3['cd'] ?>" data-prox_cita="<?php echo $mostrar3['prox_cita'] ?>">Editar</button> <button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" data-id="<?php echo $mostrar3['id'] ?>" data-id_historia="<?php echo $mostrar2['id'] ?>">Eliminar</button>

           </td></tr>
           </table>

           <?php 
            } }
            ?>

        </div>
      </div>
		</div>
	</section>

<!-- Modal Ingresar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="php/ingresartratamiento.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ingresar tratamiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
         		<label for="fecha">Fecha</label>
        		<input type="date" name="fecha" id="fecha" placeholder="Nombre" class="form-control">
            <input type="hidden" id="id_historia" name="id_historia">       		
        	</div>
        	<br>
        	<div>
         		<label for="tratamiento">Tratamiento</label>
        		<textarea name="tratamiento" id="tratamiento" cols="30" rows="5" class="form-control"></textarea>
        	</div>
        	<br>
        	<div>
         		<label for="cd">CD</label>
            <textarea name="cd" id="cd" cols="30" rows="5" class="form-control"></textarea> 
        	</div>
          <br>
          <div>
            <label for="prox_cita">Próxima cita</label>
            <input type="date" name="prox_cita" id="prox_cita" class="form-control">
          </div>
          <br>
             
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
      <form action="php/editartratamiento.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel">Editar tratamiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
            <label for="fechaEdit">Fecha</label>
            <input type="date" name="fecha" id="fechaEdit" placeholder="Nombre" class="form-control">
            <input type="hidden" id="idEdit" name="id">
            <input type="hidden" id="id_historiaEdit" name="id_historia">           
          </div>
          <br>
          <div>
            <label for="tratamientoEdit">Tratamiento</label>
            <textarea name="tratamiento" id="tratamientoEdit" cols="30" rows="5" class="form-control"></textarea>
          </div>
          <br>
          <div>
            <label for="cdEdit">CD</label>
            <textarea name="cd" id="cdEdit" cols="30" rows="5" class="form-control"></textarea> 
          </div>
          <br>
          <div>
            <label for="prox_citaEdit">Próxima cita</label>
            <input type="date" name="prox_cita" id="prox_citaEdit" class="form-control">
          </div>
          <br>
          
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
      <form action="php/eliminartratamiento2.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEliminarLabel">Eliminar tratamiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div>
            ¿Deseas eliminar?
            <input type="hidden" id="idEliminar" name="idEliminar">
            <input type="hidden" id="idHistoriaEliminar" name="idHistoriaEliminar" value="<?php echo $_REQUEST['id'] ?>">           
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

	<script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script>
      $(document).ready(function() {
        // Función para actualizar la tabla de tratamientos del cliente
        function actualizarTablaTratamientos() {
          var id_cliente = <?php echo $_GET['id']; ?>;
          $.ajax({
            url: 'php/obtener_tratamientos_cliente.php',
            method: 'GET',
            data: { id: id_cliente },
            dataType: 'json'
          }).done(function(response) {
            var tablaHTML = `
              <tr class="header">
                <th style="width:20%;">Fecha</th>
                <th style="width:20%;">Procedimiento</th>
                <th style="width:20%;">Doctor</th>
                <th style="width:20%;">Descripción</th>
                <th style="width:20%;">Acciones</th>
              </tr>
            `;
            
            response.forEach(function(tratamiento) {
              tablaHTML += `
                <tr>
                  <td>${tratamiento.fecha}</td>
                  <td>${tratamiento.procedimiento}</td>
                  <td>${tratamiento.doctor}</td>
                  <td>${tratamiento.descripcion}</td>
                  <td>
                    <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" 
                      data-id="${tratamiento.id}" data-descripcion="${tratamiento.descripcion}" 
                      data-id_producto="${tratamiento.id_producto}" data-id_doctor="${tratamiento.id_doctor}">
                      <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" 
                      data-id="${tratamiento.id}">
                      <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
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
            $("#id_productoEdit").val($(this).data('id_producto'));
            $("#id_doctorEdit").val($(this).data('id_doctor'));
            $("#descripcionEdit").val($(this).data('descripcion'));
          });
        }

        var idEliminar = -1;
        var fila;

        // Activar eventos iniciales
        activarEventosBotones();

        // Configurar actualización cada 3 minutos
        setInterval(actualizarTablaTratamientos, 180000);

        // Eliminar tratamiento
        $(".eliminarFila").click(function() {
          $.ajax({
            url: 'php/eliminartratamiento2.php',
            method: 'POST',
            data: {
              id: idEliminar
            }
          }).done(function() {
            $(fila).fadeOut(500, function() {
              actualizarTablaTratamientos();
            });
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
</body>
</html>