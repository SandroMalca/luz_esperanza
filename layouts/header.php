<nav class="navbar navbar-expand-lg navbar-light" style="background-color: #496f99 !important;">
  <a class="navbar-brand" href="index.php"><img src="img/LOGO.png" alt="" style="margin-right: 0px; padding-right: 0px; width: 70px; height: 60px;"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
    <ul class="navbar-nav">
      <li class="nav-item active">
        <a class="nav-link" href="index.php">Inicio</a>
      </li>    
      <?php if ($varsesion == 'Admin' || $varsesion == 'Superadmin') { ?>
      <li class="nav-item active">
        <a class="nav-link" href="cliente.php">Pacientes</a>
      </li>      
      <?php } ?>
      <?php if ($varsesion == 'Superadmin') { ?>
      <li class="nav-item active">
        <a class="nav-link" href="inventario.php">Consultas</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="caja.php">Caja</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="users.php">Usuarios</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link" href="eventos.php">Calendario</a>
      </li>
      <!-- <li class="nav-item active">
        <a class="nav-link" href="lista.php">Lista</a>
      </li> -->
      <?php } ?>
      <li class="nav-item active">
        <a class="nav-link" href="php/cerrar_sesion.php"><span style="color: black;">Cerrar Sesión</span></a>
      </li>
    </ul>
  </div>
</nav>