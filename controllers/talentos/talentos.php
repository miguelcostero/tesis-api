<?php
require __DIR__ . '/../../config/db.php';

if (isset($_GET['query'])) {
  $sql = 'SELECT t.* FROM talentos t WHERE t.nombre LIKE \'%'.$_GET['query'].'%\'';
} else {
  $sql = 'SELECT t.* FROM talentos t';
}

if ($result = $con->query($sql)) {
  if (!$result->num_rows > 0) {
    http_response_code(200);
    echo json_encode(array('error' => array('code' => 200, 'message' => 'No se han encontrado resultados')));
    die();
  }
  $talentos = array();

  while ($talento = $result->fetch_object()) {
    $sql = 'SELECT t.* FROM telefonos t INNER JOIN telefono_talento tt ON t.id = tt.id_telefono WHERE tt.id_talento = \''.$talento->id.'\'';
    if ($resultado = $con->query($sql)) {
      if ($resultado->num_rows > 0) {
        $telefonos = array();
        while ($telefono = $resultado->fetch_object()) {
          array_push($telefonos, $telefono);
        }
        $talento->telefonos = $telefonos;
      }
    }
    array_push($talentos, $talento); 
  }

  echo json_encode($talentos);
  $result->close();
} else {
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido procesar su solicitud', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
  die();
}
