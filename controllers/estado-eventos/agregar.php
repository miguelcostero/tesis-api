<?php
require __DIR__ . '/../../config/db.php';

if ($body = json_decode(file_get_contents('php://input'))) {
  if (isset($body->estado_evento->nombre)) {
    $estado_evento = $body->estado_evento;
    $sql = 'INSERT INTO estado_evento (id, nombre) VALUES (DEFAULT,  \''.$estado_evento->nombre.'\')';
    if ($result = $con->query($sql)) {
      $estado_evento->id = $con->insert_id;
      http_response_code(200);
      echo json_encode(array('estado_evento' => $estado_evento));
    } else {
      http_response_code(500);
      echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar el estado de evento', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}