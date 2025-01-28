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

  if($varsesion == 'Asistente'){
    header("Location: index.php");
    die();

  }
  
$resultado = $conexion -> query("select * from lista order by lista.nombre asc");

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
          <?php 
      if ($varsesion != 'Asistente') {
     ?> <button class="btn btn-success btn-small" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-plus" aria-hidden="true"></i></button> &nbsp;&nbsp;&nbsp; <a href="listapdf.php">Exportar a PDF</a><?php } ?> 
          
          <br><br>

            <table id="myTable">
            <tr class="header">
              <th style="width: 25%;">Material o Producto</th>
              <th style="width: 25%;">Descripción</th>
              <th style="width: 25%;">Cantidad</th>
              <th style="width: 25%;">Acciones</th>
            </tr>

           <?php 
            while ($mostrar=mysqli_fetch_assoc($resultado))  {
             ?>
            <tr>
              <td>
                <?php echo $mostrar['nombre'] ?>
              </td>
              <td>
                <?php echo $mostrar['descripcion'] ?>
              </td>
              <td>
                <?php echo $mostrar['cantidad'] ?>
              </td>
              <td>
                <?php 
      if ($varsesion != 'Asistente') {
        
     ?>
                <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $mostrar['id'] ?>" data-nombre="<?php echo $mostrar['nombre'] ?>" data-descripcion="<?php echo $mostrar['descripcion'] ?>" data-cantidad="<?php echo $mostrar['cantidad'] ?>" >Editar</button> <button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" data-id="<?php echo $mostrar['id'] ?>">Eliminar</button>

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
      <form action="php/ingresarlista.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ingresar lista</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
         		<label for="nombre">Nombre</label>
        		<input type="text" name="nombre" id="nombre" placeholder="Nombre" class="form-control">      		
        	</div>
          <br>
          <div>
            <label for="descripcion">Descripción</label>
            <textarea name="descripcion" id="descripcion" cols="30" rows="10" class="form-control"></textarea> 
          </div>
          <br>
        	<div>
            <label for="cantidad">Cantidad</label>
            <input type="number" id="cantidad" name="cantidad" class="form-control"> 
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
      <form action="php/editarlista.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel">Editar lista</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
            <label for="nombreEdit">Nombre</label>
            <input type="text" name="nombre" id="nombreEdit" placeholder="Nombre" class="form-control">  
            <input type="hidden" id="idEdit" name="id">        
          </div>
          <br>
          <div>
            <label for="descripcionEdit">Descripción</label>
            <textarea name="descripcion" id="descripcionEdit" cols="30" rows="10" class="form-control"></textarea> 
          </div>
          <br>
          <div>
            <label for="cantidadEdit">Monto en tarjeta</label>
            <input type="number" id="cantidadEdit" name="cantidad" class="form-control"> 
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
      <form action="php/eliminarlista.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEliminarLabel">Eliminar lista</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <div>
            ¿Deseas eliminar?
            <input type="hidden" id="idEliminar" name="idEliminar">           
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
	  $(document).ready(function(){
	    var idEditar= -1;
	    $(".btnEditar").click(function(){
	      idEditar = $(this).data('id');
        var cantidad = $(this).data('cantidad');
        var nombre = $(this).data('nombre');
	      var descripcion = $(this).data('descripcion');
	      $("#idEdit").val(idEditar);
        $("#cantidadEdit").val(cantidad);
        $("#nombreEdit").val(nombre);
	      $("#descripcionEdit").val(descripcion);
	    });
      $(".btnEliminar").click(function(){
        idEliminar = $(this).data('id');
        $("#idEliminar").val(idEliminar);
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