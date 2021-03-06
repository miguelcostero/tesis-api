<?php
require __DIR__ . '/../../config/db.php';

if ($body = json_decode(file_get_contents('php://input'))) {
  if (
    isset($body->locacion->nombre) &&
    isset($body->locacion->direccion) &&
    isset($body->locacion->capacidad) &&
    isset($body->locacion->telefonos)
  ) {
    $locacion = $body->locacion;
    $sql = 'UPDATE locaciones SET nombre = \''.$locacion->nombre.'\', direccion = \''.$locacion->direccion.'\', capacidad = \''.$locacion->capacidad.'\' WHERE id = \''.$id.'\'';
    if ($result = $con->query($sql)) {
      $locacion->id = $id;

      foreach ($locacion->telefonos as $telefono) {
        if (!isset($telefono->id)) {
          $sql = 'INSERT INTO telefonos (id, numero, prefijo, pais) VALUES (DEFAULT, \''.$telefono->numero.'\', \''.$telefono->prefijo.'\', \''.$telefono->pais.'\')';
          if ($con->query($sql)) {
            $id_telefono = $con->insert_id;
            $sql = 'INSERT INTO telefono_locacion (id_telefono, id_locacion) VALUES (\''.$id_telefono.'\', \''.$id.'\')';
            if (!$con->query($sql)) {
              http_response_code(500);
              echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido actualizar la locación'.$id, 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
              $con->rollback();
              die();
            }
          } else {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido actualizar la locación'.$id, 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
            $con->rollback();
            die();
          }
        } else {
          $sql = 'UPDATE telefonos t INNER JOIN telefono_locacion tl ON t.id = tl.id_telefono SET t.prefijo = \''.$telefono->prefijo.'\', t.numero = \''.$telefono->numero.'\', t.pais = \''.$telefono->pais.'\' WHERE tl.id_locacion = \''.$locacion->id.'\' AND t.id = \''.$telefono->id.'\'';
          if (!$resultado = $con->query($sql)) {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar numero de telefono', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
            die();
          }
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