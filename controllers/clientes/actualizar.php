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
    $con->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);

    $sql = 'CALL updateCliente(\''.$body->cliente->nombre.'\', \''.$body->cliente->email.'\', \''.$body->cliente->direccion.'\', \''.$id.'\', \''.$body->cliente->dni.'\')';

    if ($result = $con->query($sql)) {
      foreach ($body->cliente->telefonos as $telefono) {
        if (!isset($telefono->id)) {
          http_response_code(400);
          echo json_encode(array('error' => array('code' => 400, 'message' => 'No se ha proveído el id del telefono.')));
          $con->rollback();
          die();
        }

        $sql = 'UPDATE telefonos SET prefijo = \''.$telefono->prefijo.'\', numero = \''.$telefono->numero.'\', pais = \''.$telefono->pais.'\' WHERE telefonos.id = \''.$telefono->id.'\'';
        if (!$result = $con->query($sql)) {
          http_response_code(500);
          echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido actualizar el cliente '.$id)));
          $con->rollback();
          die();
        }
      }

      http_response_code(200);
      echo json_encode($body->cliente);
      $con->commit();
    } else {
      $con->rollback();
      if ($con->errno == 1062) {
        http_response_code(400);

        if (strpos($con->error, 'email')) {
          echo json_encode(array('error' => array('code' => 400, 'message' => 'El email \''.$body->cliente->email.'\' ya se encuentra registrado almacenado')));
        } else {
          echo json_encode(array('error' => array('code' => 400, 'message' => 'El dni \''.$body->cliente->dni.'\' ya se encuentra registrado almacenado')));
        }
        die();        
      } else {
        http_response_code(500);
        echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido ejecutar el update', 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
        die();        
      }
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