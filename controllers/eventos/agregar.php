<?php
require __DIR__ . '/../../config/db.php';

if ($evento = json_decode(file_get_contents('php://input'))) {
  if (
    isset($evento->nombre) &&
    isset($evento->descripcion) &&
    isset($evento->fecha_agregado) &&
    isset($evento->icono) &&
    isset($evento->invitados) &&
    isset($evento->tipo_evento->id) &&
    isset($evento->empleado->id) &&
    isset($evento->locacion->id) &&
    isset($evento->cliente->id) &&
    isset($evento->estado_evento->id) &&
    isset($evento->cronograma)
  ) {
    $sql = 'INSERT INTO eventos (id, id_tipo_evento, id_empleado, id_locacion, id_cliente, id_estado, nombre, descripcion, fecha_agregado, icono, invitados) VALUES (DEFAULT, \''.$evento->tipo_evento->id.'\', \''.$evento->empleado->id.'\', \''.$evento->locacion->id.'\', \''.$evento->cliente->id.'\', \''.$evento->estado_evento->id.'\', \''.$evento->nombre.'\', \''.$evento->descripcion.'\', \''.$evento->fecha_agregado.'\', \''.$evento->icono.'\', \''.$evento->invitados.'\')';
    if ($result = $con->query($sql)) {
      $evento->id = $con->insert_id;

      foreach ($evento->cronograma as $env_in) {
        $sql = 'INSERT INTO env_in (id, id_evento, descripcion, fecha, hora) VALUES (DEFAULT, \''.$evento->id.'\', \''.$env_in->descripcion.'\', \''.$env_in->fecha.'\', \''.$env_in->hora.'\')';
        if (!$resultado = $con->query($sql)) {
          http_response_code(400);
          echo json_encode(array('error' => array('code' => 400, 'message' => 'No se ha podido guardar el coronograma satisfactoriamiente', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
          die();
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