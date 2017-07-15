<?php
require __DIR__ . '/../../config/db.php';

$sql = 'SELECT ee.* FROM estado_evento ee WHERE ee.id = \''.$id.'\'';
if ($result = $con->query($sql)) {
  if (!$result->num_rows > 0) {
    http_response_code(200);
    echo json_encode(array('error' => array('code' => 200, 'message' => 'No se han encontrado resultados')));
    die();
  }
  $estado_eventos = array();

  while ($estado_evento = $result->fetch_object()) {
    array_push($estado_eventos, $estado_evento); 
  }

  echo json_encode($estado_eventos);
  $result->close();
} else {
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Error en consulta 1')));
  die();
}