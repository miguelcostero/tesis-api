<?php
require __DIR__ . '/../../config/db.php';

if ($locacion = json_decode(file_get_contents('php://input'))) {
  if (
    isset($locacion->nombre) &&
    isset($locacion->direccion) &&
    isset($locacion->capacidad) &&
    isset($locacion->telefonos)
  ) {
    $sql = 'UPDATE locaciones SET nombre = \''.$locacion->nombre.'\', direccion = \''.$locacion->direccion.'\', capacidad = \''.$locacion->capacidad.'\' WHERE id = \''.$id.'\'';
    if ($result = $con->query($sql)) {
      $locacion->id = $id;

      foreach ($locacion->telefonos as $telefono) {
        $sql = 'UPDATE telefonos t INNER JOIN telefono_locacion tl ON t.id = tl.id_telefono SET t.prefijo = \''.$telefono->prefijo.'\', t.numero = \''.$telefono->numero.'\', t.pais = \''.$telefono->pais.'\' WHERE tl.id_locacion = \''.$locacion->id.'\' AND t.id = \''.$telefono->id.'\'';
        if (!$resultado = $con->query($sql)) {
          http_response_code(500);
          echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar numero de telefono', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
          die();
        }
      }

      http_response_code(200);
      echo json_encode(array('locacion' => $locacion));
    } else {
      http_response_code(500);
      echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar la locacion', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}