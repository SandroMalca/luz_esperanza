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
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendario de Eventos - Luz de Esperanza</title>
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css">
    <style>
        #calendar {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 10px;
        }
        .fc-event {
            cursor: pointer;
            height: 50px;
        }        
    </style>
</head>
<body>
    <?php include "./layouts/header.php" ?>

    <div class="container-fluid mt-4">
        <div class="row">
            <div class="col-md-12">
                <h2 class="text-center mb-4">Calendario de Eventos</h2>
                <?php if ($varsesion != 'Admin') { ?>
                <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#modalEvento" style="display:block; margin: 0 auto;">
                    <i class="fa fa-plus"></i> Nuevo Evento
                </button>
                <?php } ?>
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal para Crear/Editar Evento -->
    <div class="modal fade" id="modalEvento" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formEvento">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tituloModal">Nueva Cita</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="idEvento" name="id">
                        
                        <div class="form-group">
                            <label for="paciente">Nombre del Paciente <span class="text-danger">*</span></label>
                            <select class="form-control" id="paciente" name="paciente" required>
                                <option value="">Seleccione un paciente</option>
                                <?php 
                                $query_pacientes = "SELECT id, nombre, ape1, ape2 FROM persona WHERE id_tipopersona='2' AND status='1' ORDER BY ape1, ape2, nombre ASC";
                                $resultado_pacientes = $conexion->query($query_pacientes);
                                while ($paciente = mysqli_fetch_assoc($resultado_pacientes)) {
                                    echo "<option value='".$paciente['id']."'>".$paciente['ape1']." ".$paciente['ape2'].", ".$paciente['nombre']."</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="doctor">Doctor <span class="text-danger">*</span></label>
                            <select class="form-control" id="doctor" name="doctor" required>
                                <option value="">Seleccione un doctor</option>
                                <?php 
                                $query_doctores = "SELECT id, nombre, ape1, ape2, especialidad FROM persona WHERE id_tipopersona='1' ORDER BY ape1, ape2, nombre ASC";
                                $resultado_doctores = $conexion->query($query_doctores);
                                while ($doctor = mysqli_fetch_assoc($resultado_doctores)) {
                                    echo "<option value='".$doctor['id']."'>".$doctor['ape1']." ".$doctor['ape2'].", ".$doctor['nombre']." (".$doctor['especialidad'].")</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fecha">Fecha <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="fecha" name="fecha" required min="<?php echo date('Y-m-d'); ?>">
                        </div>

                        <div class="form-group">
                            <label for="horario">Horario <span class="text-danger">*</span></label>
                            <select class="form-control" id="horario" name="horario" required>
                                <option value="">Seleccione un horario</option>
                                <option value="09:00-10:00">9:00 AM - 10:00 AM</option>
                                <option value="10:00-11:00">10:00 AM - 11:00 AM</option>
                                <option value="11:00-12:00">11:00 AM - 12:00 PM</option>
                                <option value="12:00-13:00">12:00 PM - 1:00 PM</option>
                                <option value="14:00-15:00">2:00 PM - 3:00 PM</option>
                                <option value="15:00-16:00">3:00 PM - 4:00 PM</option>
                                <option value="16:00-17:00">4:00 PM - 5:00 PM</option>
                                <option value="17:00-18:00">5:00 PM - 6:00 PM</option>
                                <option value="18:00-19:00">6:00 PM - 7:00 PM</option>
                                <option value="19:00-20:00">7:00 PM - 8:00 PM</option>
                                <option value="20:00-21:00">8:00 PM - 9:00 PM</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                        <?php if ($varsesion != 'Admin') { ?>
                        <button type="button" class="btn btn-danger" id="btnEliminar" style="display: none;">Eliminar</button>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales-all.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'es',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                },
                initialView: 'dayGridMonth',
                events: 'php/obtener_eventos.php',
                editable: <?php echo ($varsesion != 'Admin') ? 'true' : 'false' ?>,
                selectable: <?php echo ($varsesion != 'Admin') ? 'true' : 'false' ?>,
                eventClick: function(info) {
                    mostrarEvento(info.event);
                },
                select: function(info) {
                    $('#modalEvento').modal('show');
                    $('#fecha_inicio').val(info.startStr);
                    $('#fecha_fin').val(info.endStr);
                }
            });
            calendar.render();

            // Manejar el formulario de eventos
            $('#formEvento').on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);

                $.ajax({
                    url: 'php/guardar_evento.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#modalEvento').modal('hide');
                            calendar.refetchEvents();                          
                        } else {
                            alert('Error: ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Error al procesar la solicitud');
                    }
                });
            });

            // Eliminar evento
            $('#btnEliminar').click(function() {
                if (confirm('¿Está seguro de eliminar este evento?')) {
                    var id = $('#idEvento').val();
                    $.ajax({
                        url: 'php/eliminar_evento.php',
                        type: 'POST',
                        data: {id: id},
                        dataType: 'json',
                        success: function(response) {
                            if (response.success) {
                                $('#modalEvento').modal('hide');
                                calendar.refetchEvents();
                                limpiarFormulario();
                            } else {
                                alert('Error: ' + response.message);
                            }
                        }
                    });
                }
            });

            function mostrarEvento(event) {
                $('#idEvento').val(event.id);
                $('#paciente').val(event.extendedProps.id_paciente);
                $('#doctor').val(event.extendedProps.id_doctor);
                $('#fecha').val(event.extendedProps.fecha);
                $('#horario').val(event.extendedProps.horario);
                $('#btnEliminar').show();
                $('#tituloModal').text('Editar Cita');
                $('#modalEvento').modal('show');
            }

            function limpiarFormulario() {
                $('#formEvento')[0].reset();
                $('#idEvento').val('');
                $('#btnEliminar').hide();
                $('#tituloModal').text('Nueva Cita');
            }

            $('#modalEvento').on('hidden.bs.modal', function() {
                limpiarFormulario();
            });
        });
    </script>
</body>
</html> 