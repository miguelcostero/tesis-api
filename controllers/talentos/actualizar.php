<?php
require __DIR__ . '/../../config/db.php';

if ($body = json_decode(file_get_contents('php://input'))) {
  if (
    isset($body->talento->nombre) &&
    isset($body->talento->email)
  ) {
    $talento = $body->talento;

    # Iniciar transaccion
    $con->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);

    if (isset($talento->notas)) {
      $sql = 'UPDATE talentos SET nombre = \''.$talento->nombre.'\', email = \''.$talento->email.'\', notas = \''.$talento->notas.'\' WHERE id = \''.$id.'\'';
    } else {
      $sql = 'UPDATE talentos SET nombre = \''.$talento->nombre.'\', email = \''.$talento->email.'\' WHERE id = \''.$id.'\'';
    }

    if ($con->query($sql)) {
      $talento->id = $id;

      foreach ($talento->telefonos as $telefono) {
        if (!isset($telefono->id)) {
          $sql = 'INSERT INTO telefonos (id, numero, prefijo, pais) VALUES (DEFAULT, \''.$telefono->numero.'\', \''.$telefono->prefijo.'\', \''.$telefono->pais.'\')';
          if ($con->query($sql)) {
            $id_telefono = $con->insert_id;
            $sql = 'INSERT INTO telefono_talento (id_telefono, id_talento) VALUES (\''.$id_telefono.'\', \''.$id.'\')';
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
          $sql = 'UPDATE telefonos t INNER JOIN telefono_talento tt ON t.id = tt.id_telefono SET t.prefijo = \''.$telefono->prefijo.'\', t.numero = \''.$telefono->numero.'\', t.pais = \''.$telefono->pais.'\' WHERE tt.id_talento = \''.$talento->id.'\' AND t.id = \''.$telefono->id.'\'';
          if (!$resultado = $con->query($sql)) {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar numero de telefono', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
            $con->rollback();
            die();
          }
        }
      }

      $con->commit();
      http_response_code(200);
    } else {
      http_response_code(500);
      echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar la locacion', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
      $con->rollback();
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}