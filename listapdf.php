<?php 

	require_once __DIR__ . '/vendor/autoload.php';
	include "./php/conexion.php";
date_default_timezone_set('America/Lima');

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
      <div id="logo" align="center">
        <img src="img/soledent.png" width="300">
      </div>
      <div align="center">
      <b style="font-size: 20px;">Lista de compra<b>
      <div>
    </header>
    <main>
    <br><br>
      <table id="myTable">
        <tr class="header">
              <th style="width: 40%;">Material o Producto</th>
              <th style="width: 40%;">Descripción</th>
              <th style="width: 20%;">Cantidad</th>
        </tr>';
$resultado = $conexion -> query("select * from lista order by lista.nombre asc");
while ($mostrar=mysqli_fetch_assoc($resultado))  {

          $html.='<tr>
          <td>'
  			  .$mostrar['nombre'].
          '</td>
          <td>'
          .$mostrar['descripcion'].
          '</td>
          <td>'
          .$mostrar['cantidad'].
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