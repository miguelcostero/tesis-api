<?php
require __DIR__ . '/../../config/db.php';

# Iniciar transaccion
$con->begin_transaction(MYSQLI_TRANS_START_WITH_CONSISTENT_SNAPSHOT);

$sql = 'DELETE FROM locaciones WHERE locaciones.id = \''.$id.'\'';
if ($result = $con->query($sql)) {
  if ($con->affected_rows > 0) {
    $con->commit();
    http_response_code(200);
    echo json_encode(array('code' => 200, 'message' => 'Se ha eliminado satisfactoriamente ls locacion '.$id));
  } else {
    $con->rollback();
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'No se ha encontrado un locacion con el id especificado')));
  }  
} else {
  $con->rollback();
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Ha ocurrido un error al procesar su solicitud.', 'mysql_error' => $con->error, 'mysql_errno' => $con->errno)));
}