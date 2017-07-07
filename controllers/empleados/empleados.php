<?php
require __DIR__ . '/../../config/db.php';

if (isset($_GET['busqueda'])) {
  die($_GET['busqueda']);
}

$sql = 'SELECT e.* FROM empleados e';
if ($result = $con->query($sql)) {
  if (!$result->num_rows > 0) {
    http_response_code(200);
    echo json_encode(array('error' => array('code' => 200, 'message' => 'No se han encontrado resultados')));
    die();
  }
  $empleados = array();

  while ($e = $result->fetch_object()) {
    $empleado = new stdClass();
    $empleado->id = $e->id;
    $empleado->email = $e->email;
    $empleado->nombre = $e->nombre;
    $empleado->apellido = $e->apellido;
    $empleado->fecha_nacimiento = $e->fecha_nacimiento;
    $empleado->img_perfil = $e->img_perfil;    

    $sql = 'SELECT r.* FROM roles r WHERE r.id = \''.$e->role.'\'';
    if ($resultado = $con->query($sql)) {
      if ($resultado->num_rows > 0) {
        $empleado->role = $resultado->fetch_object();
      }
    }

    $sql = 'SELECT t.* FROM telefonos t INNER JOIN telefono_empleado te ON t.id = te.id_telefono WHERE te.id_empleado = \''.$empleado->id.'\'';
    if ($resultado = $con->query($sql)) {
      if ($resultado->num_rows > 0) {
        $telefonos = array();
        while ($telefono = $resultado->fetch_object()) {
          array_push($telefonos, $telefono);
        }
        $empleado->telefonos = $telefonos;
      }
    }
    array_push($empleados, $empleado); 
  }

  echo json_encode($empleados);
  $result->close();
} else {
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Error en consulta 1')));
  die();
}