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
      $sql = 'INSERT INTO talentos (id, nombre, email, notas) VALUES (DEFAULT, \''.$talento->nombre.'\', \''.$talento->email.'\', \''.$talento->notas.'\')';      
    } else {
      $sql = 'INSERT INTO talentos (id, nombre, email) VALUES (DEFAULT, \''.$talento->nombre.'\', \''.$talento->email.'\')';  
    }
    
    if ($con->query($sql)) {
      $talento->id = $con->insert_id;

      if (isset($talento->telefonos)) {
        $telefonos = array();
        foreach ($talento->telefonos as $telefono) {
          $sql = 'INSERT INTO telefonos (id, numero, prefijo, pais) VALUES (DEFAULT, \''.$telefono->numero.'\', \''.$telefono->prefijo.'\', \''.$telefono->pais.'\')';
          if ($con->query($sql)) {
            $telefono->id = $con->insert_id;
          } else {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido procesar su solicitud', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
            $con->rollback();            
            die();
          }

          $sql = 'INSERT INTO telefono_talento (id_talento, id_telefono) VALUES (\''.$talento->id.'\', \''.$telefono->id.'\')';
          if (!$con->query($sql)) {
            http_response_code(500);
            echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido procesar su solicitud', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
            $con->rollback();            
            die();
          }
          array_push($telefonos, $telefono);
        }
        $talento->telefonos = $telefonos;
      }
      
      $con->commit();
      http_response_code(201);
    } else {
      http_response_code(500);
      echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido procesar su solicitud', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
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