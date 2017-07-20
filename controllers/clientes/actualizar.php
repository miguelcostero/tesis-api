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

    $sql = 'UPDATE clientes SET dni = \''.$body->cliente->dni.'\', nombre = \''.$body->cliente->nombre.'\', email = \''.$body->cliente->email.'\', direccion = \''.$body->cliente->direccion.'\' WHERE id = \''.$id.'\'';

    if ($con->query($sql)) {
      foreach ($body->cliente->telefonos as $telefono) {
        if (!isset($telefono->id)) {
          $sql = 'INSERT INTO telefonos (id, numero, prefijo, pais) VALUES (DEFAULT, \''.$telefono->numero.'\', \''.$telefono->prefijo.'\', \''.$telefono->pais.'\')';
          if ($con->query($sql)) {
            $id_telefono = $con->insert_id;
            $sql = 'INSERT INTO telefono_cliente (id_telefono, id_cliente) VALUES (\''.$id_telefono.'\', \''.$id.'\')';
            if (!$con->query($sql)) {
              http_response_code(500);
              echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido actualizar el cliente '.$id, 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
              $con->rollback();
              die();
            }
          } else {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido actualizar el cliente '.$id, 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
            $con->rollback();
            die();
          }
        } else {
          $sql = 'UPDATE telefonos SET prefijo = \''.$telefono->prefijo.'\', numero = \''.$telefono->numero.'\', pais = \''.$telefono->pais.'\' WHERE telefonos.id = \''.$telefono->id.'\'';
          if (!$con->query($sql)) {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido actualizar el cliente '.$id, 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
            $con->rollback();
            die();
          }
        }
      }

      http_response_code(200);
      $con->commit();
    } else {
      if ($con->errno == 1062) {
        http_response_code(400);

        if (strpos($con->error, 'email')) {
          echo json_encode(array('error' => array('code' => 400, 'message' => 'El email \''.$body->cliente->email.'\' ya se encuentra registrado')));
        } else {
          echo json_encode(array('error' => array('code' => 400, 'message' => 'El dni \''.$body->cliente->dni.'\' ya se encuentra registrado')));
        }
        die();        
      } else {
        http_response_code(500);
        echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido ejecutar el update', 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));      
      }
      $con->rollback();
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