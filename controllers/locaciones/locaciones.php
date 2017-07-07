<?php
require __DIR__ . '/../../config/db.php';

$sql = 'SELECT l.* FROM locaciones l';
if ($result = $con->query($sql)) {
  if (!$result->num_rows > 0) {
    http_response_code(200);
    echo json_encode(array('error' => array('code' => 200, 'message' => 'No se han encontrado resultados')));
    die();
  }
  $locaciones = array();

  while ($locacion = $result->fetch_object()) {
    $sql = 'SELECT t.* FROM telefonos t INNER JOIN telefono_locacion tl ON t.id = tl.id_telefono WHERE tl.id_locacion = \''.$locacion->id.'\'';
    if ($resultado = $con->query($sql)) {
      if ($resultado->num_rows > 0) {
        $telefonos = array();
        while ($telefono = $resultado->fetch_object()) {
          array_push($telefonos, $telefono);
        }
        $locacion->telefonos = $telefonos;
      }
    }
    array_push($locaciones, $locacion); 
  }

  echo json_encode($locaciones);
  $result->close();
} else {
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Error en consulta 1')));
  die();
}