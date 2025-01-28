<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Consultorios Medicos Especializados Luz de Esperanza</title>
    <meta name="description" content="Consultorios Medicos Especializados Luz de Esperanza">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/estilos.css">
	<style>
		#contenedor{
			margin: auto;
			display: flex;
			text-align: center;
			justify-content: center;
		    align-items: center;
		}

		#marco{
			margin-top: 200px;
			text-align: center;
			justify-content: center;
		    align-items: center;
			background-color: white;
		    width: 40%;
		    border-radius: 10px;
		}
		
		@media (max-width: 550px){
		    #marco{
			margin-top: 200px;
			text-align: center;
			justify-content: center;
		    align-items: center;
			background-color: white;
		    width: 80%;
		    border-radius: 10px;

		}
		}
	</style>
</head>
<body style="background-color: #004d7b;">
	<div id="contenedor">
		<div id="marco">
			<br>
			<img src="img/LOGO.png" alt="" width="30%"><br><br>
			<form action="php/check.php" method="post">
				<b>Ingreso al Sistema</b> <br><br>
				<label for="">Usuario:</label><br>
				<input type="text" name="user" required><br><br>
				<label for="">Contraseña:</label><br>
				<input type="password" name="password" required><br><br>
				<button type="submit" class="btn btn-info">Ingresar</button><br><br>
			</form>
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
		</div>
	</div>



	<script src="js/jquery-3.5.1.slim.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>