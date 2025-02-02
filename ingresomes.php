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

$fecha = date('Y-m-d');

$conexion->query("SET lc_time_names = 'es_ES'");

$html = '<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>Consultorios Medicos Especializados Luz de Esperanza</title>
    <link rel="stylesheet" href="estilos.css" media="all" />
    <style>
    #myTable tr.header, #myTable tr:hover {
      background-color: #f1f1f1;
      font-family: Arial, Helvetica, sans-serif;
    }

    #myTable tr {
      border-bottom: 1px solid #ddd;
    }

    #myTable {
      border-collapse: collapse;
      width: 100%;
      border: 1px solid #ddd;
    }

    #myTable th, #myTable td {
        text-align: left;
        padding: 12px;
        font-family: Arial, Helvetica, sans-serif;
    }

    #myTable tr.header{
      font-size: 18px;
      font-family: Arial, Helvetica, sans-serif;
    }
    </style>
  </head>
  <body>
    <header class="clearfix">
      <div align="center">
      <b style="font-size: 20px;font-family: Arial, Helvetica, sans-serif;">Ingresos por mes<b>
      <div>
    </header>
    <main>
    <br><br>
      <table id="myTable">
        <tr class="header">
          <th style="width:15%;">Mes</th>
          <th style="width:15%;">Año</th>
          <th style="width:20%;">Efectivo</th>
          <th style="width:20%;">Tarjeta</th>
          <th style="width:15%;">Egreso</th>
          <th style="width:15%;">Total</th>
        </tr>';

$resultadoPago = $conexion->query("select monthname(pago.fecha) as mes, year(pago.fecha) as ano, sum(pago.efectivo) as efectivo, sum(pago.tarjeta) as tarjeta from pago GROUP by mes, ano order by ano desc, mes desc");

while ($mostrarPago = mysqli_fetch_array($resultadoPago)) {
    $resultadoEgreso = $conexion->query("select sum(egreso.egreso) as egreso from egreso where monthname(egreso.fecha)='" . $mostrarPago['mes'] . "' and year(egreso.fecha)='" . $mostrarPago['ano'] . "'");
    $mostrarEgreso = mysqli_fetch_assoc($resultadoEgreso);
    $total = $mostrarPago['efectivo'] + $mostrarPago['tarjeta'] - $mostrarEgreso['egreso'];

    $html .= '<tr>
        <td>' . $mostrarPago['mes'] . '</td>
        <td>' . $mostrarPago['ano'] . '</td>
        <td>S/ ' . number_format($mostrarPago['efectivo'], 2) . '</td>
        <td>S/ ' . number_format($mostrarPago['tarjeta'], 2) . '</td>
        <td>S/ ' . number_format($mostrarEgreso['egreso'], 2) . '</td>
        <td>S/ ' . number_format($total, 2) . '</td>
    </tr>';
}

$html .= '</table>
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