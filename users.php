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

if ($varsesion != 'Superadmin') {
  header("Location: index.php");
  die();
}

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
    <br><br>
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
          <div class="container">
            <input type="text" id="myInput" onkeyup="myFunction()" placeholder="Buscar..."> &nbsp; &nbsp; 
            <button class="btn btn-primary" style="background-color:rgb(41, 182, 111);border-color:transparent;" data-toggle="modal" data-target="#exampleModalUser">
                <i class="fa fa-user-plus" style="font-size: 1.5em;" aria-hidden="true"></i>
            </button>
            <br><br>
            <table id="myTable">
              <tr class="header">
                <th style="width:60%;">Usuario</th>
                <th style="width:40%;">Acciones</th>
              </tr>
              <?php
              $resultado = $conexion->query("select * from logueo order by logueo.user asc");
              while ($mostrar = mysqli_fetch_assoc($resultado)) {
              ?>
                <tr>
                  <td>
                    <span id="nombre"><?php echo $mostrar['user'] ?></span><br>
                    <span id="datos">
                      Tipo de acceso: <?php echo $mostrar['acceso'] ?><br>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-primary btn-small btnEditarUser" data-toggle="modal" data-target="#modalEditarUser" data-id="<?php echo $mostrar['id'] ?>" data-user="<?php echo $mostrar['user'] ?>" data-acceso="<?php echo $mostrar['acceso'] ?>" data-password="<?php echo $mostrar['password'] ?>"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    <button class="btn btn-info btn-small btnPassUser" data-toggle="modal" data-target="#modalPassUser" data-id="<?php echo $mostrar['id'] ?>" data-user="<?php echo $mostrar['user'] ?>" data-acceso="<?php echo $mostrar['acceso'] ?>" data-password="<?php echo $mostrar['password'] ?>"><i class="fa fa-lock" aria-hidden="true"></i></button>
                  </td>
                </tr>
              <?php
              }
              ?>
            </table>
          </div>
          <br><br>
        </div>
        <div class="col-12 col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6">
          <div class="container">
            <input type="text" id="myInput2" onkeyup="myFunction2()" placeholder="Buscar..."> &nbsp; &nbsp; <a href=""
              data-toggle="modal" data-target="#exampleModal"><button class="btn btn-info" style="width: 9%;justify-content: center;"><i class="fa fa-user-md" style="font-size: 1.5em;"></i></button></a>
            <br><br>
            <table id="myTable2">
              <tr class="header">
                <th style="width:60%;">Doctor</th>
                <th style="width:40%;">Acciones</th>
              </tr>
              <?php
              $resultadoDoctor = $conexion->query("select id, nombre, ape1, ape2, cmp, especialidad from persona where id_tipopersona='1' ORDER BY ape1, ape2, nombre ASC");
              while ($mostrarDoctor = mysqli_fetch_assoc($resultadoDoctor)) {
              ?>
                <tr>
                  <td>
                    <span id="nombre"><?php echo $mostrarDoctor['nombre'] ?> <?php echo $mostrarDoctor['ape1'] ?> <?php echo $mostrarDoctor['ape2'] ?></span><br>
                    <span id="datos">
                      CMP: <?php echo $mostrarDoctor['cmp'] ?><br>
                      Especialidad: <?php echo $mostrarDoctor['especialidad'] ?> <br>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" data-id="<?php echo $mostrarDoctor['id'] ?>"
                      data-nombre="<?php echo $mostrarDoctor['nombre'] ?>" data-ape1="<?php echo $mostrarDoctor['ape1'] ?>"
                      data-ape2="<?php echo $mostrarDoctor['ape2'] ?>" data-cmp="<?php echo $mostrarDoctor['cmp'] ?>"
                      data-especialidad="<?php echo $mostrarDoctor['especialidad'] ?>"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    <button class="btn btn-danger btn-small btnEliminarDoctor" data-toggle="modal" data-target="#modalEliminarDoctor" data-id="<?php echo $mostrarDoctor['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></button>
                  </td>
                </tr>
              <?php
              }
              ?>
            </table>
          </div>
        </div>
      </div>

    </div>
  </section>

  <!-- Modal Ingresar Doctor -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="php/ingresardoctor.php" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Ingresar doctor</h5>
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
              <label for="ape1">Apellido Paterno <span style="color: red">*</span></label>
              <input type="text" name="ape1" id="ape1" placeholder="Apellido Paterno" class="form-control" required>
            </div>
            <br>
            <div>
              <label for="ape2">Apellido Materno</label>
              <input type="text" name="ape2" id="ape2" placeholder="Apellido Materno" class="form-control">
            </div>
            <br>
            <div>
              <label for="cmp">CMP</label>
              <input type="text" name="cmp" id="cmp" placeholder="CMP" class="form-control" maxlength="6">
            </div>
            <br>
            <div>
              <label for="especialidad">Especialidad</label>
              <input type="text" name="especialidad" id="especialidad" placeholder="Especialidad" class="form-control">
            </div>
            <br>
            <input type="hidden" name="id_tipopersona" value="1">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Ingresar User-->
  <div class="modal fade" id="exampleModalUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="php/ingresaruser.php" method="post" id="formIngresarUser">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalUserLabel">Ingresar Usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="user">Usuario <span style="color: red">*</span></label>
              <input type="text" name="user" id="user" placeholder="Usuario" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="password">Contraseña <span style="color: red">*</span></label>
              <input type="password" name="password" id="password" placeholder="Contraseña" class="form-control" required>
            </div>
            <div class="form-group">
              <label for="acceso">Tipo de Acceso</label>
              <select name="acceso" id="acceso" class="form-control">
                <option value="Superadmin">Admin</option>                
                <option value="Admin">Doctor</option>
              </select>
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

  <!-- Modal Editar User-->
  <div class="modal fade" id="modalEditarUser" tabindex="-1" role="dialog" aria-labelledby="modalEditarUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="formEditarUser" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditarUserLabel">Editar Usuario</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <label for="userEdit">Usuario <span style="color: red">*</span></label>
              <input type="text" name="user" id="userEdit" placeholder="Usuario" class="form-control" required>
              <input type="hidden" name="id" id="iduserEdit" class="form-control">
            </div>
            <br>
            <div>
              <label for="accesoEdit">Tipo de Acceso</label>
              <select name="acceso" id="accesoEdit" class="form-control">
                <option value="Superadmin">Admin</option>
                <option value="Admin">Doctor</option>
              </select>
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


  <!-- Modal Editar Doctor -->
  <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form id="formEditarDoctor" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modalEditarLabel">Editar doctor</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <label for="nombreEdit">Nombre <span style="color: red">*</span></label>
              <input type="text" name="nombre" id="nombreEdit" placeholder="Nombre" class="form-control">
              <input type="hidden" name="id" id="idEdit" class="form-control">
            </div>
            <br>
            <div>
              <label for="ape1Edit">Apellido Paterno <span style="color: red">*</span></label>
              <input type="text" name="ape1" id="ape1Edit" placeholder="Apellido Paterno" class="form-control">
            </div>
            <br>
            <div>
              <label for="ape2Edit">Apellido Materno</label>
              <input type="text" name="ape2" id="ape2Edit" placeholder="Apellido Materno" class="form-control">
            </div>
            <br>
            <div>
              <label for="cmpEdit">CMP</label>
              <input type="text" name="cmp" id="cmpEdit" placeholder="CMP" class="form-control" maxlength="6">
            </div>
            <br>
            <div>
              <label for="especialidadEdit">Especialidad</label>
              <input type="text" name="especialidad" id="especialidadEdit" placeholder="Especialidad" class="form-control">
            </div>
            <br>
            <input type="hidden" name="id_tipopersona" value="1">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            <button type="submit" class="btn btn-primary">Guardar</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Editar Password -->
  <div class="modal fade" id="modalPassUser" tabindex="-1" role="dialog" aria-labelledby="modalPassUserLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form action="php/editarpassword.php" method="post">
          <div class="modal-header">
            <h5 class="modal-title" id="modalPassUserLabel">Editar password</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div>
              <label for="passwordEdit">Password <span style="color: red">*</span></label>
              <input type="text" name="password" id="passwordEdit" placeholder="Password" class="form-control">
              <input type="hidden" name="iduser" id="iduserpassEdit" class="form-control">
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

  <!-- Modal Eliminar Doctor -->
  <div class="modal fade" id="modalEliminarDoctor" tabindex="-1" role="dialog" aria-labelledby="modalEliminarDoctorLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalEliminarDoctorLabel">Eliminar doctor</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          ¿Deseas eliminar este doctor?
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          <button type="submit" class="btn btn-danger eliminarFilaDoctor" data-dismiss="modal">Eliminar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="js/jquery-3.5.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.js"></script>
  <script>
    $(document).ready(function() {
      // Función para actualizar la tabla de usuarios
      function actualizarTablaUsuarios() {
        $.ajax({
          url: 'php/obtener_usuarios.php',
          method: 'GET',
          dataType: 'json'
        }).done(function(response) {
          var tablaHTML = `
              <tr class="header">
                <th style="width:60%;">Usuario</th>
                <th style="width:40%;">Acciones</th>
              </tr>
            `;

          response.forEach(function(user) {
            tablaHTML += `
                <tr>
                  <td>
                    <span id="nombre">${user.user}</span><br>
                    <span id="datos">
                      Tipo de acceso: ${user.acceso}<br>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-primary btn-small btnEditarUser" data-toggle="modal" data-target="#modalEditarUser" 
                      data-id="${user.id}" data-user="${user.user}" data-acceso="${user.acceso}" data-password="${user.password}">
                      <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-info btn-small btnPassUser" data-toggle="modal" data-target="#modalPassUser" 
                      data-id="${user.id}" data-user="${user.user}" data-acceso="${user.acceso}" data-password="${user.password}">
                      <i class="fa fa-lock" aria-hidden="true"></i>
                    </button>
                  </td>
                </tr>
              `;
          });

          $("#myTable").html(tablaHTML);
          activarEventosUsuarios();
        });
      }

      // Función para actualizar la tabla de doctores
      function actualizarTablaDoctores() {
        $.ajax({
          url: 'php/obtener_doctores.php',
          method: 'GET',
          dataType: 'json'
        }).done(function(response) {
          var tablaHTML = `
              <tr class="header">
                <th style="width:60%;">Doctor</th>
                <th style="width:40%;">Acciones</th>
              </tr>
            `;

          response.forEach(function(doctor) {
            tablaHTML += `
                <tr>
                  <td>
                    <span id="nombre">${doctor.nombre} ${doctor.ape1} ${doctor.ape2} </span><br>
                    <span id="datos">
                      CMP: ${doctor.cmp}<br>
                      Especialidad: ${doctor.especialidad}<br>
                    </span>
                  </td>
                  <td>
                    <button class="btn btn-primary btn-small btnEditar" data-toggle="modal" data-target="#modalEditar" 
                      data-id="${doctor.id}" 
                      data-nombre="${doctor.nombre}" 
                      data-ape1="${doctor.ape1}" 
                      data-ape2="${doctor.ape2}" 
                      data-cmp="${doctor.cmp}" 
                      data-especialidad="${doctor.especialidad}">
                      <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-danger btn-small btnEliminarDoctor" data-toggle="modal" data-target="#modalEliminarDoctor" 
                      data-id="${doctor.id}">
                      <i class="fa fa-trash" aria-hidden="true"></i>
                    </button>
                  </td>
                </tr>
              `;
          });

          $("#myTable2").html(tablaHTML);
          activarEventosDoctores();
        });
      }

      // Función para activar eventos de usuarios
      function activarEventosUsuarios() {
        $(".btnEditarUser").click(function() {
          var id = $(this).data('id');
          var user = $(this).data('user');
          var acceso = $(this).data('acceso');
          $("#iduserEdit").val(id);
          $("#userEdit").val(user);
          $("#accesoEdit").val(acceso);
        });

        $(".btnPassUser").click(function() {
          var id = $(this).data('id');
          var password = $(this).data('password');
          $("#iduserpassEdit").val(id);
          $("#passwordEdit").val(password);
        });
      }

      // Función para activar eventos de doctores
      function activarEventosDoctores() {
        $(".btnEliminarDoctor").click(function() {
          idEliminarDoctor = $(this).data('id');
          filaDoctor = $(this).closest('tr');
        });

        $(".btnEditar").click(function() {
          var id = $(this).data('id');
          $("#idEdit").val(id);
          $("#nombreEdit").val($(this).data('nombre'));
          $("#ape1Edit").val($(this).data('ape1'));
          $("#ape2Edit").val($(this).data('ape2'));
          $("#cmpEdit").val($(this).data('cmp'));
          $("#especialidadEdit").val($(this).data('especialidad'));
        });
      }

      var idEliminarDoctor = -1;
      var filaDoctor;

      // Activar eventos iniciales
      activarEventosUsuarios();
      activarEventosDoctores();

      // Configurar actualización cada 5 segundos
      setInterval(function() {
        actualizarTablaUsuarios();
        actualizarTablaDoctores();
      }, 5000);

      // Eliminar doctor
      $(".eliminarFilaDoctor").click(function() {
        $.ajax({
          url: 'php/eliminardoctor.php',
          method: 'POST',
          data: {
            id: idEliminarDoctor
          }
        }).done(function() {
          $(filaDoctor).fadeOut(500, function() {
            actualizarTablaDoctores();
          });
        });
      });

      // Manejar el envío del formulario de edición de usuario
      $("#formEditarUser").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
          url: 'php/editaruser.php',
          method: 'POST',
          data: $(this).serialize(),
          dataType: 'json'
        }).done(function(response) {
          if (response.success) {
            $('#modalEditarUser').modal('hide');
            actualizarTablaUsuarios();
          }
        }).fail(function() {
          alert('Error al procesar la solicitud');
        });
      });
      // Manejar el envío del formulario de edición de doctor
      $("#formEditarDoctor").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
          url: 'php/editardoctor.php',
          method: 'POST',
          data: $(this).serialize(),
          dataType: 'json'
        }).done(function(response) {
          if (response.success) {
            $('#modalEditar').modal('hide');
            actualizarTablaDoctores();
          }
        }).fail(function(xhr) {
          alert('Error al procesar la solicitud: ' + xhr.responseText);
        });
      });
      // Función para formatear fecha
			function formatDate(dateString) {
				if (!dateString) return '';
				var parts = dateString.split('-');
				return parts[2] + '-' + parts[1] + '-' + parts[0];
			}

      // Manejar el formulario de ingreso de usuario
      $("#formIngresarUser").on('submit', function(e) {
        e.preventDefault();
        $.ajax({
          url: 'php/ingresaruser.php',
          method: 'POST',
          data: $(this).serialize(),
          dataType: 'json'
        }).done(function(response) {
          if (response.success) {
            $('#exampleModalUser').modal('hide');
            $("#formIngresarUser")[0].reset();
            actualizarTablaUsuarios();
          } else {
            alert('Error: ' + response.message);
          }
        }).fail(function(xhr, status, error) {
          console.error('Error:', xhr.responseText);
          alert('Error al procesar la solicitud. Por favor, inténtelo de nuevo.');
        });
      });

      // Limpiar formulario cuando se cierra el modal
      $('#exampleModalUser').on('hidden.bs.modal', function () {
        $("#formIngresarUser")[0].reset();
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
    function myFunction2() {
      // Declare variables
      var input, filter, table, tr, td, i, txtValue;
      input = document.getElementById("myInput2");
      filter = input.value.toUpperCase();
      table = document.getElementById("myTable2");
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
