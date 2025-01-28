<?php
  session_start();
  session_destroy();
  header("Location: ../logueo.php");
  exit;
?>