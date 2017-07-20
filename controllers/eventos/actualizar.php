<?php
require __DIR__ . '/../../config/db.php';

if ($body = json_decode(file_get_contents('php://input'))) {
  if (
    isset($body->evento->nombre) &&
    isset($body->evento->descripcion) &&
    isset($body->evento->icono) &&
    isset($body->evento->invitados) &&
    isset($body->evento->tipo_evento->id) &&
    isset($body->evento->locacion->id) &&
    isset($body->evento->cliente->id) &&
    isset($body->evento->estado_evento->id) && 
    isset($body->evento->cronograma)
  ) {
    $evento = $body->evento;
    $sql = 'UPDATE eventos SET id_tipo_evento = \''.$evento->tipo_evento->id.'\', id_locacion = \''.$evento->locacion->id.'\', id_cliente = \''.$evento->cliente->id.'\', id_estado = \''.$evento->estado_evento->id.'\', nombre = \''.$evento->nombre.'\', descripcion = \''.$evento->descripcion.'\', icono = \''.$evento->icono.'\', invitados = \''.$evento->invitados.'\' WHERE id = \''.$id.'\'';
    if ($con->query($sql)) {
      $evento->id = $id;

      $sql = 'DELETE FROM env_in WHERE id_evento = \''.$evento->id.'\'';
      if ($con->query($sql)) {
        foreach ($evento->cronograma as $c) {
          $sql = 'INSERT INTO env_in (id, id_evento, descripcion, fecha, hora, notas) VALUES (DEFAULT, \''.$evento->id.'\', \''.$c->descripcion.'\', \''.$c->fecha.'\', \''.$c->hora.'\', \''.$c->notas.'\')';
          if (!$con->query($sql)) {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido procesar su solicitud', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
            die();
          }
        }
      }

      if (isset($evento->talentos)) {
        $sql = 'DELETE FROM talento_evento WHERE id_evento = \''.$evento->id.'\'';
        if ($con->query($sql)) {
          foreach ($evento->talentos as $talento) {          
            $sql = 'INSERT INTO talento_evento (id_evento, id_talento) VALUES (\''.$evento->id.'\', \''.$talento->id.'\')';
            if (!$con->query($sql)) {
              http_response_code(500);
              echo json_encode(array('error' => array('code' => 500, 'message' => 'Ha ocurrido un error procesando su solicitud', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
              die();
            }
          }
        } else {
          http_response_code(500);
          echo json_encode(array('error' => array('code' => 500, 'message' => 'Ha ocurrido un error procesando su solicitud', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
          die(); 
        }
      }

      http_response_code(200);
      echo json_encode($evento);
    } else {
      http_response_code(400);
      echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}