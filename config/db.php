<?php
# Create a new connection
$con = $_SERVER['SERVER_NAME'] == 'localhost' ? new mysqli('127.0.0.1', 'root', '', 'tesis') : new mysqli('142.4.216.180', 'talentpr_api', 'at!k^waLid;?', 'talentpr_api');

# Verificar si hay error de conexion
if ($con->connect_errno) {
  header($_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Error');
  echo json_encode(array(
    'error' => array(
      'code' => 500,
      'message' => 'No se pudo conectar a la base de datos',
      'errno' => $con->connect_errno,
      'error' => $con->connect_error
    )
  ));
  # Terminar ejecución de la app
  die();
}

# Set charset
$con->set_charset('utf8');