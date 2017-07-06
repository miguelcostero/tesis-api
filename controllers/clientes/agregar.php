<?php
require __DIR__ . '/../../config/db.php';

if ($body = json_decode(file_get_contents('php://input'))) {

  if (
    isset($body->cliente) &&
    isset($body->cliente->dni) &&
    isset($body->cliente->nombre) &&
    isset($body->cliente->email) &&
    isset($body->cliente->direccion) &&
    isset($body->cliente->telefonos)
  ) {
    # Iniciar transaccion
    $con->autocommit(false);

    $sql = 'CALL addCliente(\''.$body->cliente->dni.'\', \''.$body->cliente->nombre.'\', \''.$body->cliente->email.'\', \''.$body->cliente->direccion.'\', @inserted_id)';

    if (!$result = $con->query($sql)) {
      if ($con->errno == 1062) {
        $con->rollback();
        http_response_code(400);

        if (strpos($con->error, 'email')) {
          echo json_encode(array('error' => array('code' => 400, 'message' => 'El email \''.$body->cliente->email.'\' ya se encuentra registrado almacenado')));
        } else {
          echo json_encode(array('error' => array('code' => 400, 'message' => 'El dni \''.$body->cliente->dni.'\' ya se encuentra registrado almacenado')));
        }
      } else {
        http_response_code(500);
        echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido ejecutar el insert', 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
      }
      die();
    }

    $sql = 'SELECT @inserted_id AS inserted_id';

    if ($result = $con->query($sql)) {
      $object = $result->fetch_object();
      $inserted_id = $object->inserted_id;
      $body->cliente->id = $inserted_id;

      foreach ($body->cliente->telefonos as $key => $telefono) {
        $sql = 'CALL addClienteTelefono(\''.$inserted_id.'\', \''.$telefono->numero.'\', \''.$telefono->prefijo.'\', \''.$telefono->pais.'\', @id_telefono)';

        if (!$result = $con->query($sql)) {
          $con->rollback();
          http_response_code(500);
          echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido ejecutar el insert', 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
          die();
        }

        $sql = 'SELECT @id_telefono AS id';
        if ($result = $con->query($sql)) {
          $id_telefono = $result->fetch_object();
          $body->cliente->telefonos[0]->id = $id_telefono->id;
        } else {
          $con->rollback();
          http_response_code(500);
          echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido ejecutar el insert', 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
          die();
        }
      }

      http_response_code(201);
      echo json_encode($body->cliente);
      $con->commit();
    } else {
      $con->rollback();
      http_response_code(500);
      echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido ejecutar el insert', 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
      die();
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
    die();
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}