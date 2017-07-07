<?php
require __DIR__ . '/../../config/db.php';

if ($locacion = json_decode(file_get_contents('php://input'))) {
  if (
    isset($locacion->nombre) &&
    isset($locacion->direccion) &&
    isset($locacion->capacidad) &&
    isset($locacion->telefonos)
  ) {
    $sql = 'INSERT INTO locaciones (id, nombre, direccion, capacidad) VALUES (DEFAULT, \''.$locacion->nombre.'\', \''.$locacion->direccion.'\', \''.$locacion->capacidad.'\')';
    if ($result = $con->query($sql)) {
      $locacion->id = $con->insert_id;
      $telefonos = array();
      foreach ($locacion->telefonos as $telefono) {
        $sql = 'INSERT INTO telefonos (id, numero, prefijo, pais) VALUES (DEFAULT, \''.$telefono->numero.'\', \''.$telefono->prefijo.'\', \''.$telefono->pais.'\')';
        if ($resultado = $con->query($sql)) {
          $telefono->id = $con->insert_id;
        } else {
          die();
        }
        $sql = 'INSERT INTO telefono_locacion (id_locacion, id_telefono) VALUES (\''.$locacion->id.'\', \''.$telefono->id.'\')';
        if (!$resultado = $con->query($sql)) {
          die();
        }
        array_push($telefonos, $telefono);
      }
      $locacion->telefonos = $telefonos;
      http_response_code(201);
      echo json_encode(array('locacion' => $locacion));
    } else {
      http_response_code(400);
      echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}