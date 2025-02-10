<?php
// api.php
// Este endpoint devuelve datos en formato JSON según el parámetro "type".
// Valores permitidos: clientes, doctores, historias, procedimientos.

header("Content-Type: application/json; charset=UTF-8");

// CONFIGURACIÓN DE LA BASE DE DATOS (AJUSTA SEGÚN TU ENTORNO)
$servidor = "localhost";
$nombreBD = "luz_esperanza";
$usuario  = "root";
$pass     = "";
$conexion = new mysqli($servidor, $usuario, $pass, $nombreBD);
if ($conexion->connect_error) {
    echo json_encode(["error" => "Error en la conexión: " . $conexion->connect_error]);
    exit;
}

// Se obtiene el parámetro "type" desde la URL
$type = isset($_GET['type']) ? $_GET['type'] : '';

switch ($type) {

    case 'clientes':
        // OBTENER CLIENTES
        $query = "SELECT 
                    persona.*, 
                    YEAR(CURDATE()) - YEAR(persona.fec_nac) + IF(DATE_FORMAT(CURDATE(),'%m-%d') > DATE_FORMAT(persona.fec_nac,'%m-%d'), 0, -1) AS edad,
                    tipo_doc.nombre AS tipodoc,
                    exploracion_fisica.pad,
                    exploracion_fisica.pas,
                    exploracion_fisica.spo2,
                    exploracion_fisica.fc,
                    exploracion_fisica.temp,
                    exploracion_fisica.peso,
                    exploracion_fisica.talla
                  FROM persona 
                  LEFT JOIN tipo_doc ON tipo_doc.id = persona.id_tipodoc 
                  LEFT JOIN exploracion_fisica ON exploracion_fisica.id_historia = persona.id
                  WHERE persona.id_tipopersona = '2' AND persona.status = 1";

        $resultado = $conexion->query($query);
        $clientes = array();
        if ($resultado) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $clientes[] = $row;
            }
            echo json_encode($clientes);
        } else {
            echo json_encode(["error" => "Error en la consulta de clientes: " . $conexion->error]);
        }
        break;

    case 'doctores':
        // OBTENER DOCTORES
        $query = "SELECT id, nombre, ape1, ape2, cmp, especialidad 
                  FROM persona 
                  WHERE id_tipopersona = '1' 
                  ORDER BY ape1, ape2, nombre ASC";
        $resultado = $conexion->query($query);
        $doctores = array();
        if ($resultado) {
            while ($doctor = mysqli_fetch_assoc($resultado)) {
                $doctores[] = array(
                    'id'           => $doctor['id'],
                    'nombre'       => $doctor['nombre'],
                    'ape1'         => $doctor['ape1'],
                    'ape2'         => $doctor['ape2'],
                    'cmp'          => $doctor['cmp'],
                    'especialidad' => $doctor['especialidad']
                );
            }
            echo json_encode($doctores);
        } else {
            echo json_encode(["error" => "Error en la consulta de doctores: " . $conexion->error]);
        }
        break;

    case 'historias':
        $query = "SELECT h.*, 
                             d.nombre AS doctor_nombre, 
                             d.ape1   AS doctor_ape1, 
                             d.ape2   AS doctor_ape2 
                      FROM historia h 
                      LEFT JOIN persona d ON h.id_doctor = d.id 
                      ORDER BY h.id DESC";
        $resultado = $conexion->query($query);
        if ($resultado) {
            $num_rows = $resultado->num_rows;
            // Puedes comentar la siguiente línea una vez que hayas verificado el número de filas:
            // error_log("Número de historias obtenidas: " . $num_rows);

            $historias = array();
            while ($historia = mysqli_fetch_assoc($resultado)) {
                $historias[] = array(
                    'id'                => $historia['id'],
                    'id_cliente'        => $historia['id_cliente'],
                    'id_doctor'         => $historia['id_doctor'],
                    'fecha'             => $historia['fecha'],
                    'motivo'            => $historia['motivo'],
                    'enfermedad_actual' => $historia['enfermedad_actual'],
                    'antec_familiar'    => $historia['antec_familiar'],
                    'antec_personales'  => $historia['antec_personales'],
                    'exam_fisico'       => $historia['exam_fisico'],
                    'diag_presuntivo'   => $historia['diag_presuntivo'],
                    'exam_auxiliar'     => $historia['exam_auxiliar'],
                    'laboratorio'       => $historia['laboratorio'],
                    'otros'             => $historia['otros'],
                    'diag_definitivo'   => $historia['diag_definitivo'],
                    'tratamiento'       => $historia['tratamiento'],
                    'doctor_nombre'     => $historia['doctor_nombre'],
                    'doctor_ape1'       => $historia['doctor_ape1'],
                    'doctor_ape2'       => $historia['doctor_ape2']
                );
            }
            echo json_encode($historias);
        } else {
            echo json_encode(["error" => "Error en la consulta de historias: " . $conexion->error]);
        }
        break;

    case 'procedimientos':
        // OBTENER PROCEDIMIENTOS
        $query = "SELECT * FROM producto ORDER BY nombre ASC";
        $resultado = $conexion->query($query);
        $procedimientos = array();
        if ($resultado) {
            while ($proc = mysqli_fetch_assoc($resultado)) {
                // Convertir el campo "horario" (cadena separada por comas) a texto legible
                $horarios = explode(',', $proc['horario']);
                $dias = array();
                foreach ($horarios as $h) {
                    switch (trim($h)) {
                        case '1':
                            $dias[] = 'Lunes';
                            break;
                        case '2':
                            $dias[] = 'Martes';
                            break;
                        case '3':
                            $dias[] = 'Miércoles';
                            break;
                        case '4':
                            $dias[] = 'Jueves';
                            break;
                        case '5':
                            $dias[] = 'Viernes';
                            break;
                        case '6':
                            $dias[] = 'Sábado';
                            break;
                    }
                }
                $proc['horario_texto'] = implode(', ', $dias);
                // Agregamos el campo "dias" para mantener compatibilidad
                $proc['dias'] = $proc['horario_texto'];
                if (isset($proc['precio'])) {
                    $proc['precio'] = floatval($proc['precio']);
                }
                $procedimientos[] = $proc;
            }
            echo json_encode($procedimientos);
        } else {
            echo json_encode(["error" => "Error en la consulta de procedimientos: " . $conexion->error]);
        }
        break;

    default:
        echo json_encode([
            "error" => "Parámetro 'type' inválido. Los valores válidos son: clientes, doctores, historias, procedimientos."
        ]);
        break;
    case 'pago':
        // OBTENER INFORMACIÓN DE PAGO PARA TODOS LOS CLIENTES (sin requerir parámetro id)
        // Se agrupa la información de pagos por cliente.
        $query = "SELECT 
            p.efectivo,
            p.tarjeta,
            DATE_FORMAT(p.fecha, '%Y-%m-%d') AS fecha,
            pers.id AS persona_id,
            pers.nombre,
            pers.ape1,
            pers.ape2,
            pers.fec_nac
          FROM pago p 
          INNER JOIN persona pers ON pers.id = p.id_cliente
          WHERE p.status = 1
          ORDER BY pers.id, p.fecha DESC";

        $resultado = $conexion->query($query);
        if (!$resultado) {
            echo json_encode(["error" => "Error en la consulta de pagos: " . $conexion->error]);
            exit;
        }

        $clientesPagos = [];
        while ($row = mysqli_fetch_assoc($resultado)) {
            $pid = $row['persona_id'];
            // Crear registro para el cliente si aún no existe
            if (!isset($clientesPagos[$pid])) {
                $clientesPagos[$pid] = [
                    "persona" => [
                        "id"      => $pid,
                        "nombre"  => $row['nombre'],
                        "ape1"    => $row['ape1'],
                        "ape2"    => $row['ape2'],
                    ],
                    "pagos" => [],
                    "resumen" => [
                        "efectivo"    => 0,
                        "tarjeta"     => 0,
                        "totalPagado" => 0,
                    ]
                ];
            }
            // Crear registro de pago (sin incluir ningún id)
            $pago = [
                "fecha"    => $row['fecha'],
                "efectivo" => floatval($row['efectivo']),
                "tarjeta"  => floatval($row['tarjeta'])
            ];
            // Agregar pago al cliente
            $clientesPagos[$pid]["pagos"][] = $pago;
            // Sumar valores para el resumen
            $clientesPagos[$pid]["resumen"]["efectivo"]    += $row['efectivo'];
            $clientesPagos[$pid]["resumen"]["tarjeta"]     += $row['tarjeta'];
            $clientesPagos[$pid]["resumen"]["totalPagado"] += $row['efectivo'] + $row['tarjeta'];
        }
        // Calcular total pagado y saldo para cada cliente
        foreach ($clientesPagos as &$clienteData) {
            $totalPagado = $clienteData["resumen"]["efectivo"] + $clienteData["resumen"]["tarjeta"];
            $clienteData["resumen"]["totalPagado"] = $totalPagado;
        }
        unset($clienteData);
        // Devolver los resultados como un arreglo indexado
        echo json_encode(array_values($clientesPagos));
        break;

    case 'egreso':
        // OBTENER LOS EGRESOS
        $query = "SELECT id, fecha, egreso, descripcion FROM egreso ORDER BY fecha DESC";
        $resultado = $conexion->query($query);
        $egresos = array();
        if ($resultado) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                // Convertir el campo "egreso" a decimal (float) para que Power BI lo reconozca
                $row['egreso'] = floatval($row['egreso']);
                $egresos[] = $row;
            }
            echo json_encode($egresos);
        } else {
            echo json_encode(["error" => "Error en la consulta de egresos: " . $conexion->error]);
        }
        break;
}


// Cerrar la conexión
$conexion->close();
