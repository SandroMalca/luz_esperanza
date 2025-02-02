<?php 

	require_once __DIR__ . '/vendor/autoload.php';
	include "./php/conexion.php";
date_default_timezone_set('America/Lima');
session_start();
error_reporting(0);
$varsesion = $_SESSION['acceso'];
$varsesion2 = $_SESSION['user'];

if ($varsesion2 == null || $varsesion2 = '') {
  header("Location: logueo.php");
  die();
}

if ($varsesion == 'Admin' || $varsesion == 'Doctor' ) {
  header("Location: index.php");
  die();
}

	$fecha=date('Y-m-d');

	$resultadoIngresoDia = $conexion -> query("select SUM(pago.efectivo) as efectivo, SUM(pago.tarjeta) as tarjeta from pago where pago.fecha='".$fecha."' ");
    $mostrarIngresoDia=mysqli_fetch_assoc($resultadoIngresoDia);
	
    $html= '<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Consultorios Medicos Especializados Luz de Esperanza</title>
    <link rel="stylesheet" href="estilos.css" media="all" />
    <style>
    #myTable tr.header, #myTable tr:hover {
  /* Add a grey background color to the table header and on hover */
  background-color: #f1f1f1;
  font-family: Arial, Helvetica, sans-serif;
}

#myTable tr {
  /* Add a bottom border to all table rows */
  border-bottom: 1px solid #ddd;
}

#myTable {
  border-collapse: collapse; /* Collapse borders */
  width: 100%; /* Full-width */
  border: 1px solid #ddd; /* Add a grey border */
  font-family: Arial, Helvetica, sans-serif;
}

#myTable th, #myTable td {
    text-align: left;
    padding: 12px;
}

#myTable tr.header{
  /* Add a grey background color to the table header and on hover */
  font-size: 18px;
}

#myTable #nombre{
	font-weight: bold;
	font-size: 16px;
}

#myTable #datos{
	font-size: 14px;
}
</style>
  </head>
  <body>
    <header class="clearfix">
      <div align="center">
      <b style="font-size: 20px;font-family: Arial, Helvetica, sans-serif;">Pacientes por día<b>
      <div>
    </header>
    <main>
    <br><br>
      <table id="myTable">
        <tr class="header">
          <th style="width:50%;">Fecha</th>
          <th style="width:50%;">Total</th>
        </tr>';
          $resultadoPacienteDia = $conexion -> query("select count(persona.id) as pacientes, persona.fecha as fecha from persona where persona.id_tipopersona=2 group by persona.fecha order by persona.fecha desc");
        while ($mostrarPacienteDia=mysqli_fetch_array($resultadoPacienteDia))  {

          $html.='<tr>
          <td>'
            .date('d-m-Y', strtotime($mostrarPacienteDia['fecha'])).
          '</td>
          <td>'
          .$mostrarPacienteDia['pacientes'].
          '</td>
        </tr>';
    }

    $html.='</table>  
    </main>
  </body>
</html>';
	$mpdf = new \Mpdf\Mpdf([
    'mode' => 'utf-8',
    'format' => [80, 258],
]);
	$mpdf->WriteHTML($html);
	$mpdf->Output();


 ?>