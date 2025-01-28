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
  font-family: Arial, Helvetica, sans-serif;
}

#myTable #nombre{
	font-weight: bold;
	font-size: 16px;
}

#myTable #datos{
	font-size: 14px;
  font-family: Arial, Helvetica, sans-serif;
}
</style>
  </head>
  <body>
    <header class="clearfix">
      <div align="center">
      <b style="font-size: 20px;font-family: Arial, Helvetica, sans-serif;">Ingresos por día<b>
      <div>
    </header>
    <main>
    <br><br>
      <table id="myTable">
        <tr class="header">
          <th style="width:20%;">Fecha</th>
          <th style="width:20%;">Efectivo</th>
          <th style="width:20%;">Tarjeta</th>
          <th style="width:20%;">Egreso</th>
          <th style="width:20%;">Total</th>
        </tr>';

        $resultadoPago = $conexion -> query("select pago.fecha as fecha, sum(pago.efectivo) as efectivo, sum(pago.tarjeta) as tarjeta from pago GROUP by pago.fecha order by pago.fecha desc "); 
        while ($mostrarPago=mysqli_fetch_array($resultadoPago))  {
          $resultadoEgreso = $conexion -> query("select sum(egreso.egreso) as egreso from egreso where egreso.fecha='".$mostrarPago['fecha']."'"); 
          $mostrarEgreso=mysqli_fetch_assoc($resultadoEgreso);
          $total=$mostrarPago['efectivo']+$mostrarPago['tarjeta']-$mostrarEgreso['egreso'];

          $html.='<tr>
          <td>'
            .date('d-m-Y', strtotime($mostrarPago['fecha'])).
          '</td>
          <td>'
            .$mostrarPago['efectivo'].
          '</td>
          <td>'
            .$mostrarPago['tarjeta'].
          '</td>
          <td>'
            .$mostrarEgreso['egreso'].
          '</td>
          <td>'
            .$total.
          '</td>
        </tr>';
    }

    $html.='</table>  
    </main>
  </body>
</html>';
	$mpdf = new \Mpdf\Mpdf();
	$mpdf->WriteHTML($html);
	$mpdf->Output();


 ?>