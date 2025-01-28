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
  
$resultado = $conexion -> query("select * from lote where id_producto=".$_REQUEST['id']." order by lote.fec_ven asc");
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
		<br><br>
		<div class="container-fluid">
			<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar..."> &nbsp; &nbsp; <a href="" data-toggle="modal" data-target="#exampleModal"><button class="btn btn-success btn-small"><i class="fa fa-shopping-cart" aria-hidden="true"></i></button></a> 
			<br><br>
			<table id="myTable">
				<tr class="header">
					<th style="width:60%;">Lote</th>
					<th style="width:40%;">Acciones</th>
				</tr>
				<?php 
				while ($mostrar=mysqli_fetch_assoc($resultado))  {
				 ?>
				<tr>
					<td>
						<span id="nombre">Fec. Venc: <?php echo $mostrar['fec_ven'] ?> </span><br>
						<span id="datos">
							Cantidad: <?php echo $mostrar['cantidad'] ?><br>
							Usado: <?php echo $mostrar['usado'] ?><br>
              Lote: <?php echo $mostrar['lote'] ?><br>
						</span>
					</td>
					<td>
            <button class="btn btn-success btn-small btnUsar" data-toggle="modal" data-target="#modalUsar" data-id="<?php echo $mostrar['id']; ?>"><i class="fa fa-minus" aria-hidden="true"></i></button>
						<button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $mostrar['id'] ?>" data-fec_ven="<?php echo $mostrar['fec_ven'] ?>" data-cantidad="<?php echo $mostrar['cantidad'] ?>" data-lote="<?php echo $mostrar['lote'] ?>"><i class="fa fa-pencil" aria-hidden="true"></i></button>
						<button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" data-id="<?php echo $mostrar['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
					</td>
				</tr>
				<?php 
				}
				 ?>
			</table>
		</div>
	</section>


<!-- Modal Usar -->
<div class="modal fade" id="modalUsar" tabindex="-1" role="dialog" aria-labelledby="modalUsarLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="php/ingresarusado.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="modalUsarLabel">Cantidad usada</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
          <br>
          <div>
            <label for="usado">Cantidad usada<span style="color: red">*</span></label>
            <input type="number" name="usado" id="usado" class="form-control">
            <input type="hidden" name="id" id="idEdit" class="form-control">
            <input type="hidden" name="id_producto" id="id_producto" value="<?php echo $_REQUEST['id'] ?>">          
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

<!-- Modal Ingresar -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="php/ingresarlote.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ingresar lote</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
         		<label for="fec_ven">Fecha de Vencimiento</label>
        		<input type="date" name="fec_ven" id="fec_ven" class="form-control"> 
            <input type="hidden" name="id" id="id" value="<?php echo $_REQUEST['id'] ?>">     		
        	</div>
        	<br>
        	<div>
            <label for="cantidad">Cantidad<span style="color: red">*</span></label>
            <input type="number" name="cantidad" id="cantidad" class="form-control">          
          </div>
        	<br>
          <div>
            <label for="lote">Lote</label>
            <input type="text" name="lote" id="lote" class="form-control">
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
      <form action="php/editarlote.php" method="post">
      <div class="modal-header">
        <h5 class="modal-title" id="modalEditarLabel">Editar producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        	<div>
            <label for="fec_ven">Fecha de Vencimiento</label>
            <input type="date" name="fec_ven" id="fec_venEdit" class="form-control">
            <input type="hidden" name="id" id="idEdit" class="form-control">
            <input type="hidden" name="id_producto" id="id_producto" value="<?php echo $_REQUEST['id'] ?>">

          </div>
          <br>
          <div>
            <label for="cantidad">Cantidad<span style="color: red">*</span></label>
            <input type="number" name="cantidad" id="cantidadEdit" class="form-control">          
          </div>
          <br>
          <div>
            <label for="lote">Lote</label>
            <input type="text" name="lote" id="loteEdit" class="form-control">
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
        <h5 class="modal-title" id="modalEliminarLabel">Eliminar Producto</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        ¿Deseas eliminar el cliente?      
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
	    var idEliminar= -1;
	    var idEditar= -1;
	    var fila;
	    $(".btnEliminar").click(function(){
	      idEliminar = $(this).data('id');
	      fila = $(this).parent('td').parent('tr');
	    });
	    $(".eliminarFila").click(function(){
	     $.ajax({
	      url: 'php/eliminarlote.php',
	      method: 'POST',
	      data:{
	        id:idEliminar
	      }
	     }).done(function(res){
	      $(fila).fadeOut(1000);
	     });
	    });
	    $(".btnEditar").click(function(){
	      idEditar = $(this).data('id');
	      var cantidad = $(this).data('cantidad');
	      var lote = $(this).data('lote');
        var fec_ven = $(this).data('fec_ven');
	      $("#idEdit").val(idEditar);
	      $("#fec_venEdit").val(fec_ven);
	      $("#cantidadEdit").val(cantidad);
        $("#loteEdit").val(lote);
	    });
      $(".btnUsar").click(function(){
        idEditar = $(this).data('id');
        $("#idEdit").val(idEditar);
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