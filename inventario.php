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

if ($varsesion == 'Admin' || $varsesion == 'Doctor' || $varsesion == 'Asistente') {
  header("Location: index.php");
  die();
}

$resultado = $conexion->query("select * from producto order by producto.nombre asc");
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Consultorio Medicos Especializados Luz de Esperanza</title>
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
    <br><br>
    <div class="container-fluid">
    <h3>Escriba el procedimiento, prueba o consulta a buscar</h3><br>
      <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar..." style="width: 500px;"> 
      &nbsp; &nbsp; <a href="" data-toggle="modal" data-target="#exampleModal">
        <button class="btn btn-success btn-small"><i class="fa fa-plus" aria-hidden="true"></i></button></a>
      <br><br>
      <table id="myTable">
        <tr class="header">
              <th style="width:25%;">Procedimiento / Prueba / Consulta</th>
              <th style="width:25%;">Acciones</th>
        </tr>
        <?php
        while ($mostrar = mysqli_fetch_assoc($resultado)) {
        ?>
          <tr>
            <td>
              <span id="nombre"><?php echo $mostrar['nombre'] ?> </span><br>
              <span id="datos">
                Precio: S/<?php echo $mostrar['precio'] ?><br>
                Horario: <?php 
                $horarios_array = explode(',', $mostrar['horario']);
                $dias = array(
                    '1' => 'Lunes',
                    '2' => 'Martes',
                    '3' => 'Miércoles',
                    '4' => 'Jueves',
                    '5' => 'Viernes',
                    '6' => 'Sábado'
                );
                $horarios_texto = array();
                foreach($horarios_array as $h) {
                    if(isset($dias[$h])) {
                        $horarios_texto[] = $dias[$h];
                    }
                }
                echo implode(', ', $horarios_texto);
                ?><br>
              </span>
            </td>
            <td>
              <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $mostrar['id'] ?>" data-nombre="<?php echo $mostrar['nombre'] ?>" data-precio="<?php echo $mostrar['precio'] ?>" data-horario="<?php echo $mostrar['horario'] ?>"><i class="fa fa-pencil" aria-hidden="true"></i></button>
              <button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" data-id="<?php echo $mostrar['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
              <!--<a href="lote.php?id=<?php echo $mostrar['id'] ?>"><button class="btn btn-success btn-small"><i class="fa fa-tasks" aria-hidden="true"></i></button></a>-->
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
        <form action="php/ingresarproducto.php" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ingresar procedimientos</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <label for="nombre">Nombre <span style="color: red">*</span></label>
              <input type="text" name="nombre" id="nombre" placeholder="Nombre" class="form-control" required>
            </div>
            <br>
            <div>
              <label for="precio">Precio<span style="color: red">*</span></label>
              <input type="number" step="0.01" min="0" name="precio" id="precio" placeholder="0.00" class="form-control" required>
            </div>
            <br>

            <div>
              <label for="horario">Horario<span style="color: red">*</span></label>
              <small class="form-text text-muted">Si no selecciona ningún horario, se establecerá por defecto "Lunes a Viernes"</small>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="horario[]" value="1" id="lunes">
                <label class="form-check-label" for="lunes">Lunes</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="horario[]" value="2" id="martes">
                <label class="form-check-label" for="martes">Martes</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="horario[]" value="3" id="miercoles">
                <label class="form-check-label" for="miercoles">Miércoles</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="horario[]" value="4" id="jueves">
                <label class="form-check-label" for="jueves">Jueves</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="horario[]" value="5" id="viernes">
                <label class="form-check-label" for="viernes">Viernes</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="horario[]" value="6" id="sabado">
                <label class="form-check-label" for="sabado">Sábado</label>
              </div>
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
        <form action="php/editarproducto.php" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditarLabel">Editar procedimiento</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <label for="nombreEdit">Nombre <span style="color: red">*</span></label>
              <input type="text" name="nombre" id="nombreEdit" placeholder="Nombre" class="form-control" required>
              <input type="hidden" name="id" id="idEdit" class="form-control">
            </div>
            <br>
            <div>
              <label for="precioEdit">Precio <span style="color: red">*</span></label>
              <input type="number" step="0.01" min="0" name="precio" id="precioEdit" placeholder="0.00" class="form-control" required>
            </div>
            <br>
            <div>
              <label for="horarioEdit">Horario<span style="color: red">*</span></label>
              <small class="form-text text-muted">Si no selecciona ningún horario, se establecerá por defecto "Lunes a Viernes"</small>
              <?php 
              $dias = array(
                  '1' => 'Lunes',
                  '2' => 'Martes', 
                  '3' => 'Miércoles',
                  '4' => 'Jueves',
                  '5' => 'Viernes',
                  '6' => 'Sábado',
              );
              foreach($dias as $valor => $dia) { ?>
                <div class="form-check">
                  <input class="form-check-input horario-edit" type="checkbox" name="horario[]" value="<?php echo $valor; ?>" id="<?php echo strtolower($dia); ?>Edit">
                  <label class="form-check-label" for="<?php echo strtolower($dia); ?>Edit"><?php echo $dia; ?></label>
                </div>
              <?php } ?>
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
          <h5 class="modal-title" id="modalEliminarLabel">Eliminar procedimiento</h5>
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
    $(document).ready(function() {
      // Función para actualizar la tabla de procedimientos
      function actualizarTabla() {
        $.ajax({
          url: 'php/obtener_procedimientos.php',
          method: 'GET',
          dataType: 'json'
        }).done(function(response) {
          var tablaHTML = `
            <tr class="header">
              <th style="width:50%;">Procedimiento/Prueba/Consulta</th>           
              <th style="width:50%;">Acciones</th>
            </tr>
          `;
          
          response.forEach(function(proc) {
            tablaHTML += `
              <tr>
                <td>
                  <span id="nombre">${proc.nombre}</span><br>
                  <span id="datos">
                    Precio: S/${proc.precio}<br>
                    Horario: ${proc.horario_texto}<br>
                  </span>
                </td>
                <td>
                  <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" 
                    data-id="${proc.id}" data-nombre="${proc.nombre}" data-precio="${proc.precio}" data-horario="${proc.horario}">
                    <i class="fa fa-pencil" aria-hidden="true"></i>
                  </button>
                  <button class="btn btn-danger btn-small btnEliminar" data-toggle="modal" data-target="#modalEliminar" 
                    data-id="${proc.id}">
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

      // Función para activar los eventos de los botones
      function activarEventosBotones() {
        $(".btnEliminar").click(function() {
          idEliminar = $(this).data('id');
          fila = $(this).closest('tr');
        });

        $(".btnEditar").click(function() {
          var id = $(this).data('id');
          var nombre = $(this).data('nombre');
          var precio = $(this).data('precio');
          var horario = $(this).data('horario');
          
          $("#idEdit").val(id);
          $("#nombreEdit").val(nombre);
          $("#precioEdit").val(precio);
          
          // Marcar los checkboxes del horario
          var horarios = horario.split(',');
          $("input[name='horario[]']").prop('checked', false);
          horarios.forEach(function(h) {
            $("input[name='horario[]'][value='" + h + "']").prop('checked', true);
          });
        });
      }

      var idEliminar = -1;
      var fila;

      // Activar eventos iniciales
      activarEventosBotones();

      // Configurar actualización cada 5 segundos
      setInterval(actualizarTabla, 60000);

      // Eliminar procedimiento
      $(".eliminarFila").click(function() {
        $.ajax({
          url: 'php/eliminarproducto.php',
          method: 'POST',
          data: {
            id: idEliminar
          }
        }).done(function() {
          $(fila).fadeOut(500, function() {
            actualizarTabla();
          });
        });
      });
    });

    function getDayName(value) {
      var days = {
        '1': 'lunes',
        '2': 'martes',
        '3': 'miercoles',
        '4': 'jueves',
        '5': 'viernes',
        '6': 'sabado'
      };
      return days[value] || '';
    }
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