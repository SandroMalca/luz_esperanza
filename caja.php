<?php 
include "php/conexion.php";
date_default_timezone_set('America/Lima');
 session_start();
 error_reporting(0);
 $varsesion = $_SESSION['acceso'];
 $varsesion2 = $_SESSION['user'];

  if($varsesion2 == null || $varsesion2 == ''){
    header("Location: logueo.php");
    die();

  }

  if($varsesion == 'Asistente'){
    header("Location: index.php");
    die();

  }

$fecha = date('Y-m-d');

$resultado4 = $conexion -> query("select sum(pago.efectivo) as efectivo, sum(pago.tarjeta) as tarjeta from pago where fecha='".$fecha."'");
$mostrar4=mysqli_fetch_assoc($resultado4);

$resultado5 = $conexion -> query("select sum(egreso.egreso) as egreso from egreso where fecha='".$fecha."'");
$mostrar5=mysqli_fetch_assoc($resultado5);

$totalefectivo = $mostrar4['efectivo'] - $mostrar5['egreso'];

$total = $totalefectivo + $mostrar4['tarjeta'];

$resultado6 = $conexion -> query("select * from egreso where fecha='".$fecha."'");
 
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
		<div class="container">
      <?php $fecha = date('d-m-Y') ?>
      <div align="center"><b><?php echo htmlspecialchars($fecha) ?></b></div><br>
			<div class="row">
        <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
          <div align="center"><b>Ingresos del día</b></div> <br><br>

          Efectivo: <?php echo htmlspecialchars($mostrar4['efectivo']); ?><br>
          Tarjeta: <?php echo htmlspecialchars($mostrar4['tarjeta']); ?><br>

          <br><br>

        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
          <div align="center"><b>Egresos del día</b> &nbsp;&nbsp;&nbsp; <button class="btn btn-danger btn-small" data-toggle="modal" data-target="#exampleModal">
            <i class="fa fa-minus" aria-hidden="true"></i></button></div>   <br><br>

          <?php while ($mostrar6=mysqli_fetch_assoc($resultado6)) {
           ?>

           <table id="myTable">
             <tr>
               <td style="width: 60%; font-size: 15px; padding-top: 0px; padding-bottom: 0px;">
                  <?php echo $mostrar6['descripcion']; ?>
               </td>
               <td style="width: 20%; font-size: 15px; padding-top: 0px; padding-bottom: 0px;">
                 <?php echo $mostrar6['egreso']; ?>
               </td>
               <td style="width: 20%; font-size: 10px; padding-top: 0px; padding-bottom: 0px;">
                  <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo htmlspecialchars($mostrar6['id']) ?>" 
                  data-egreso="<?php echo htmlspecialchars($mostrar6['egreso']) ?>" data-descripcion="<?php echo htmlspecialchars($mostrar6['descripcion']) ?>">
                  <i class="fa fa-pencil" aria-hidden="true"></i></button> <button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" 
                  data-id="<?php echo $mostrar6['id'] ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
               </td>
             </tr>
           </table>


          <?php } ?>

          <br><br>

        </div>
      </div>
      <br><br>
      <div align="center">
        
        Total efectivo: <?php echo htmlspecialchars($totalefectivo); ?><br>
        Total tarjeta: <?php echo htmlspecialchars($mostrar4['tarjeta']); ?><br>
        -------------------------------------- <br>
        <b>Total del día: <?php echo htmlspecialchars($total); ?></b><br>

          
      </div><br>
		</div>
	</section>

  <section>
    <br><br>
    <div class="container">
      
    <table id="myTable">
        <tr class="header">
          <th style="width:20%;">Fecha</th>
          <th style="width:20%;">Efectivo</th>
          <th style="width:20%;">Tarjeta</th>
          <th style="width:20%;">Egreso</th>
          <th style="width:20%;">Total</th>
        </tr>
        <?php
        $resultadoPago = $conexion -> query("select pago.fecha as fecha, sum(pago.efectivo) as efectivo, sum(pago.tarjeta) as tarjeta from pago GROUP by pago.fecha order by pago.fecha desc "); 
        while ($mostrarPago=mysqli_fetch_array($resultadoPago))  {
          $resultadoEgreso = $conexion -> query("select sum(egreso.egreso) as egreso from egreso where egreso.fecha='".$mostrarPago['fecha']."'"); 
          $mostrarEgreso=mysqli_fetch_assoc($resultadoEgreso);
          $total=$mostrarPago['efectivo']+$mostrarPago['tarjeta']-$mostrarEgreso['egreso'];
         ?>
        <tr>
          <td>
            <?php echo htmlspecialchars($mostrarPago['fecha']) ?>
          </td>
          <td>
            <?php echo htmlspecialchars($mostrarPago['efectivo']) ?>
          </td>
          <td>
            <?php echo htmlspecialchars($mostrarPago['tarjeta']) ?>
          </td>
          <td>
            <?php echo htmlspecialchars($mostrarEgreso['egreso']) ?>
          </td>
          <td>
            <?php echo htmlspecialchars($total) ?>
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
      <form action="php/ingresaregreso.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ingresar egreso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
            <label for="egreso">Monto de egreso</label>
            <input type="number" step=".01" id="egreso" name="egreso" class="form-control">
          </div>
        	<br>
        	<div>
         		<label for="descripcion">Concepto</label>
            <textarea name="descripcion" id="descripcion" cols="30" rows="5" class="form-control"></textarea> 
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
      <form action="php/editaregreso.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel">Editar tratamiento</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
            <label for="egresoEdit">Monto de egreso</label>
            <input type="number" step=".01" id="egresoEdit" name="egreso" class="form-control">
            <input type="hidden" id="idEdit" name="id">
          </div>
          <br>
          <div>
            <label for="descripcionEdit">Descripción</label>
            <textarea name="descripcion" id="descripcionEdit" cols="30" rows="5" class="form-control"></textarea> 
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
        <h5 class="modal-title" id="modalEliminarLabel">Eliminar egreso</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Deseas eliminar el egreso?      
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
        var idEditar = -1;
        var fila;

        $(".btnEliminar").click(function(){
          idEliminar = $(this).data('id');
          fila = $(this).closest('tr');
        });

        $(".eliminarFila").click(function(){
          $.ajax({
            url: 'php/eliminaregreso.php',
            method: 'POST',
            data: {
              id: idEliminar
            }
          }).done(function() {
            $(fila).fadeOut(500, function() {
              $(this).remove();
              location.reload();
            });
          });
        });

        $(".btnEditar").click(function(){
          idEditar = $(this).data('id');
          var egreso = $(this).data('egreso');
          var descripcion = $(this).data('descripcion');
          $("#idEdit").val(idEditar);
          $("#egresoEdit").val(egreso);
          $("#descripcionEdit").val(descripcion);
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