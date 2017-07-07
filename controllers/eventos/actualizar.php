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
    isset($evento->estado_evento->id)
  ) {
    $sql = 'UPDATE eventos SET id_tipo_evento = \''.$evento->tipo_evento->id.'\', id_empleado = \''.$evento->empleado->id.'\', id_locacion = \''.$evento->locacion->id.'\', id_cliente = \''.$evento->cliente->id.'\', id_estado = \''.$evento->estado_evento->id.'\', nombre = \''.$evento->nombre.'\', descripcion = \''.$evento->descripcion.'\', fecha_agregado = \''.$evento->fecha_agregado.'\', icono = \''.$evento->icono.'\', invitados = \''.$evento->invitados.'\' WHERE id = \''.$id.'\'';
    if ($result = $con->query($sql)) {
      $evento->id = $id;
      http_response_code(200);
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