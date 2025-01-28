<?php 
include "./conexion.php";
date_default_timezone_set('America/Lima');

// Obtener todos los procedimientos
$resultado = $conexion->query("SELECT * FROM producto ORDER BY nombre ASC");
$procedimientos = array();

while($proc = mysqli_fetch_assoc($resultado)) {
    // Convertir horario a texto legible
    $horarios = explode(',', $proc['horario']);
    $dias = array();
    foreach($horarios as $h) {
        switch($h) {
            case '1': $dias[] = 'Lunes'; break;
            case '2': $dias[] = 'Martes'; break;
            case '3': $dias[] = 'Miércoles'; break;
            case '4': $dias[] = 'Jueves'; break;
            case '5': $dias[] = 'Viernes'; break;
            case '6': $dias[] = 'Sábado'; break;
        }
    }
    $proc['horario_texto'] = implode(', ', $dias);
    $proc['dias'] = $proc['horario_texto']; // Añadimos el campo dias para mantener compatibilidad
    $procedimientos[] = $proc;
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($procedimientos);
?> 