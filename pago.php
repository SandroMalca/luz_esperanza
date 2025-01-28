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
  
  if($varsesion == 'Doctor'){
    header("Location: index.php");
    die();
  }
  
$resultado = $conexion -> query("select persona.*, YEAR(CURDATE())-YEAR(persona.fec_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(persona.fec_nac,'%m-%d'), 0 , -1 ) as edad, tipo_doc.nombre as tipodoc from persona LEFT JOIN tipo_doc on tipo_doc.id=persona.id_tipodoc where persona.id='".$_REQUEST['id']."' ORDER BY ape1, ape2, nombre ASC");
$mostrar=mysqli_fetch_assoc($resultado);

$resultado = $conexion->query("SELECT 
    pago.*,
    DATE_FORMAT(pago.fecha, '%Y-%m-%d') as fecha,
    persona.nombre,
    persona.ape1,
    persona.ape2
FROM pago 
INNER JOIN persona ON persona.id = pago.id_cliente
WHERE pago.id_cliente = '".$_REQUEST['id']."' AND pago.status = 1
ORDER BY pago.fecha DESC") or die($conexion->error);

$resultado4 = $conexion->query("SELECT 
    SUM(pago.efectivo) as efectivo, 
    SUM(pago.tarjeta) as tarjeta 
FROM pago 
WHERE id_cliente = '".$_REQUEST['id']."' 
AND status = 1") or die($conexion->error);
$mostrar4=mysqli_fetch_assoc($resultado4);

$montopagar = $mostrar3['monto'];
$efectivo = $mostrar4['efectivo'];
$tarjeta = $mostrar4['tarjeta'];

$totalpagado = $efectivo + $tarjeta;
$total = $totalpagado - $montopagar;
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Consultorios Medicos Especializados Luz de Esperanza</title>
    <meta name="description" content="Consultorios Medicos Especializados Luz de Esperanza">
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
                Tipo Documento: <?php echo $mostrar['tipodoc'] ?><br>
								Nro. Doc: <?php echo $mostrar['nrodoc'] ?> <br>
								Fec. Nacimiento: <?php echo date('d-m-Y', strtotime($mostrar['fec_nac'])) ?> (Edad: <?php echo $mostrar['edad'] ?>) <br>
								Estado Civil: <?php echo $mostrar['estado_civil'] ?> <br>
								Sexo: <?php echo $mostrar['sexo'] ?> <br>
          </span>
          <br><br><br><br>
        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8">

          <?php
            /*if ($total>=0) {
              if ($total>0) {
            ?>
            <b>Monto a favor del cliente: </b> <b style="color: blue;"><?php echo $total ?></b> <br><br>
          <?php } else{ ?>
            <b>Cliente al día </b><br><br>
          <?php } } else{
            $total2 = $total*(-1);
             ?>
             <b>Monto que adeuda el cliente: </b> <b style="color: red;"><?php echo $total2 ?></b> <br><br>
           <?php }*/  ?>

          <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar..."> &nbsp; &nbsp;<?php 
      if ($varsesion == 'Admin' || $varsesion == 'Superadmin') {
     ?> <button class="btn btn-success btn-small" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus" aria-hidden="true"></i></button> <?php } ?>
          
          <br><br>

            <table id="myTable">
            <tr class="header">
              <th style="width: 10%;">Fecha</th>
              <th style="width: 10%;">Efectivo</th>
              <th style="width: 10%;">Tarjeta</th>
              <th style="width: 60%;">Descripción</th>
              <th style="width: 10%;">Acciones</th>
            </tr>

           <?php 
            while ($mostrar2=mysqli_fetch_assoc($resultado))  {
             ?>
            <tr>
              <td>
                <?php echo date('d-m-Y', strtotime($mostrar2['fecha'])) ?>
              </td>
              <td>
                <?php echo $mostrar2['efectivo'] ?>
              </td>
              <td>
                <?php echo $mostrar2['tarjeta'] ?>
              </td>
              <td>
                <?php echo $mostrar2['descripcion'] ?>
              </td>
              <td>
                <?php 
      if ($varsesion == 'Superadmin') {
        
     ?>
                <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $mostrar2['id'] ?>" data-fecha="<?php echo $mostrar2['fecha'] ?>" data-efectivo="<?php echo $mostrar2['efectivo'] ?>" data-tarjeta="<?php echo $mostrar2['tarjeta'] ?>" data-descripcion="<?php echo $mostrar2['descripcion'] ?>">
                  <i class="fa fa-pencil" aria-hidden="true"></i>
                </button>
                <button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" data-id="<?php echo $mostrar2['id'] ?>" data-id_cliente="<?php echo $_REQUEST['id'] ?>">
                  <i class="fa fa-trash" aria-hidden="true"></i>
                </button>

              <?php } ?>

              </td>
            </tr>
            <?php 
            }
            ?>
           </table>

           

        </div>
      </div>
		</div>
	</section>

<!-- Modal Ingresar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="php/ingresarpago.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ingresar Pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
         		<label for="fecha">Fecha</label>
        		<input type="date" name="fecha" id="fecha" placeholder="Nombre" class="form-control" value="<?php echo date('Y-m-d') ?>">
            <input type="hidden" id="id_cliente" name="id_cliente" value="<?php echo $_REQUEST['id'] ?>">       		
        	</div>
        	<br>
        	<div>
            <label for="efectivo">Monto en efectivo</label>
            <input type="number" step=".01" id="efectivo" name="efectivo" class="form-control"> 
          </div>
        	<br>
        	<div>
            <label for="tarjeta">Monto en tarjeta</label>
            <input type="number" step=".01" id="tarjeta" name="tarjeta" class="form-control"> 
          </div>
          <br>
          <div>
            <label for="descripcion">Descripción</label>
            <input type="text" name="descripcion" id="descripcion" class="form-control">
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
      <form action="php/editarpago.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel">Editar Pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
            <label for="fechaEdit">Fecha</label>
            <input type="date" name="fecha" id="fechaEdit" placeholder="Nombre" class="form-control" value="<?php echo date('Y-m-d') ?>">
            <input type="hidden" id="id_clienteEdit" name="id_cliente" value="<?php echo $_REQUEST['id'] ?>">   
            <input type="hidden" id="idEdit" name="id">        
          </div>
          <br>
          <div>
            <label for="efectivoEdit">Monto en efectivo</label>
            <input type="number" step=".01" id="efectivoEdit" name="efectivo" class="form-control"> 
          </div>
          <br>
          <div>
            <label for="tarjetaEdit">Monto en tarjeta</label>
            <input type="number" step=".01" id="tarjetaEdit" name="tarjeta" class="form-control"> 
          </div>
          <br>
          <div>
            <label for="descripcionEdit">Descripción</label>
            <textarea name="descripcion" id="descripcionEdit" cols="10" rows="10" class="form-control"></textarea> 
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
      <div class="modal-header">
        <h5 class="modal-title" id="modalEliminarLabel">Eliminar Pago</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Deseas eliminar el Pago?      
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
	  $(document).ready(function(){
	    var idEliminar = -1;
	    var id_cliente = -1;
	    var fila;

	    $(".btnEliminar").click(function(){
	      idEliminar = $(this).data('id');
	      id_cliente = $(this).data('id_cliente');
	    });

	    $(".eliminarFila").click(function(){
	      $.ajax({
	        url: 'php/eliminarpago.php',
	        method: 'POST',
	        data: {
	          id: idEliminar,
	          id_cliente: id_cliente
	        }
	      }).done(function(response) {
	        $('#modalEliminar').modal('hide');
	        actualizarTablaPagos();
	      }).fail(function(jqXHR, textStatus, errorThrown) {
	        console.error('Error:', textStatus, errorThrown);
	        alert('Error al eliminar el pago');
	      });
	    });

	    $(".btnEditar").click(function(){
	      idEditar = $(this).data('id');
	      var fecha = $(this).data('fecha');
	      var efectivo = $(this).data('efectivo');
	      var tarjeta = $(this).data('tarjeta');
	      var descripcion = $(this).data('descripcion');
	      $("#idEdit").val(idEditar);
	      $("#fechaEdit").val(fecha);
	      $("#efectivoEdit").val(efectivo);
	      $("#tarjetaEdit").val(tarjeta);
	      $("#descripcionEdit").val(descripcion);
	    });

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

  <!-- Función para actualizar la tabla de pagos -->
  <script>
    function actualizarTablaPagos() {
      window.location.reload();
    }
  </script>
</body>
</html>