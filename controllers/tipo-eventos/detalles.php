<?php
require __DIR__ . '/../../config/db.php';

$sql = 'SELECT te.* FROM tipo_evento te WHERE te.id = \''.$id.'\'';
if ($result = $con->query($sql)) {
  if (!$result->num_rows > 0) {
    http_response_code(200);
    echo json_encode(array('error' => array('code' => 200, 'message' => 'No se han encontrado resultados')));
    die();
  }
  $tipo_eventos = array();

  while ($tipo_evento = $result->fetch_object()) {
    array_push($tipo_eventos, $tipo_evento); 
  }

  echo json_encode($tipo_eventos);
  $result->close();
} else {
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Error en consulta 1')));
  die();
}