<?php
require __DIR__ . '/../../config/db.php';

if ($body = json_decode(file_get_contents('php://input'))) {
  if (
    isset($body->evento->nombre) &&
    isset($body->evento->descripcion) &&
    isset($body->evento->icono) &&
    isset($body->evento->invitados) &&
    isset($body->evento->tipo_evento->id) &&
    isset($body->evento->empleado->id) &&
    isset($body->evento->locacion->id) &&
    isset($body->evento->cliente->id) &&
    isset($body->evento->estado_evento->id) &&
    isset($body->evento->cronograma)
  ) {
    $evento = $body->evento;
    $sql = 'INSERT INTO eventos (id, id_tipo_evento, id_empleado, id_locacion, id_cliente, id_estado, nombre, descripcion, fecha_agregado, icono, invitados) VALUES (DEFAULT, \''.$evento->tipo_evento->id.'\', \''.$evento->empleado->id.'\', \''.$evento->locacion->id.'\', \''.$evento->cliente->id.'\', \''.$evento->estado_evento->id.'\', \''.$evento->nombre.'\', \''.$evento->descripcion.'\', NOW(), \''.$evento->icono.'\', \''.$evento->invitados.'\')';
    if ($result = $con->query($sql)) {
      $evento->id = $con->insert_id;

      foreach ($evento->cronograma as $env_in) {
        $sql = 'INSERT INTO env_in (id, id_evento, descripcion, fecha, hora, notas) VALUES (DEFAULT, \''.$evento->id.'\', \''.$env_in->descripcion.'\', \''.$env_in->fecha.'\', \''.$env_in->hora.'\', \''.$env_in->notas.'\')';
        if (!$resultado = $con->query($sql)) {
          http_response_code(400);
          echo json_encode(array('error' => array('code' => 400, 'message' => 'No se ha podido guardar el coronograma satisfactoriamiente', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
          die();
        }
      }

      if (isset($evento->talentos)) {
        foreach ($evento->talentos as $talento) {          
          $sql = 'INSERT INTO talento_evento (id_evento, id_talento) VALUES (\''.$evento->id.'\', \''.$talento->id.'\')';
          if (!$con->query($sql)) {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'Ha ocurrido un error procesando su solicitud', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
            die();
          }
        }
      }

      http_response_code(201);
      echo json_encode(array('evento' => $evento));
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