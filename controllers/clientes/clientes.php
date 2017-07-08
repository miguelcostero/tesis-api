<?php
require __DIR__ . '/../../config/db.php';

if (isset($_GET['query'])) {
  $sql = 'SELECT c.* FROM clientes c WHERE c.nombre LIKE \'%'.$_GET['query'].'%\' OR c.apellido LIKE \'%'.$_GET['query'].'%\'';
} else {
  $sql = 'SELECT c.* FROM clientes c';
}

if ($result = $con->query($sql)) {
  $clientes = array();

  if (!$result->num_rows > 0) {
    http_response_code(200);
    echo json_encode(array('error' => array('code' => 200, 'message' => 'No se han encontrafo resultados')));
    die();
  }

  while ($c = $result->fetch_object()) {
    $cliente = new stdClass();
    $cliente->id = $c->id;
    $cliente->dni = $c->dni;
    $cliente->nombre = $c->nombre;
    $cliente->email = $c->email;
    $cliente->direccion = $c->direccion;

    $sql = 'SELECT t.* FROM telefonos t INNER JOIN telefono_cliente tc ON t.id = tc.id_telefono WHERE tc.id_cliente = \''.$c->id.'\'';
    if ($resultado = $con->query($sql)) {
      if ($resultado->num_rows > 0) {
        $cliente->telefonos = array();
        while ($telefono = $resultado->fetch_object()) {
          array_push($cliente->telefonos, $telefono);
        }
      }
    }

    array_push($clientes, $cliente);
  }

  http_response_code(200);
  echo json_encode($clientes);
} else {
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Error en consulta 1')));
  die();
}