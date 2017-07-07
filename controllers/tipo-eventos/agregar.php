<?php
require __DIR__ . '/../../config/db.php';

if ($tipo_evento = json_decode(file_get_contents('php://input'))) {
  if (isset($tipo_evento->nombre)) {
    $sql = 'INSERT INTO tipo_evento (id, nombre) VALUES (DEFAULT,  \''.$tipo_evento->nombre.'\')';
    if ($result = $con->query($sql)) {
      $tipo_evento->id = $con->insert_id;
      http_response_code(200);
      echo json_encode(array('tipo_evento' => $tipo_evento));
    } else {
      http_response_code(500);
      echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar el tipo de evento', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}