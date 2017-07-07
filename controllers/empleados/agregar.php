<?php
require __DIR__ . '/../../config/db.php';

if ($empleado = json_decode(file_get_contents('php://input'))) {
  if (
    isset($empleado->email) &&
    isset($empleado->password) &&
    isset($empleado->nombre) &&
    isset($empleado->apellido) &&
    isset($empleado->fecha_nacimiento) &&
    isset($empleado->img_perfil) &&
    isset($empleado->role) &&
    isset($empleado->telefonos)
  ) {
    $sql = 'INSERT INTO empleados (id, email, password, nombre, apellido, fecha_nacimiento, role, img_perfil) VALUES (DEFAULT, \''.$empleado->email.'\', MD5(\''.$empleado->password.'\'), \''.$empleado->nombre.'\', \''.$empleado->apellido.'\', \''.$empleado->fecha_nacimiento.'\', \''.$empleado->role->id.'\', \''.$empleado->img_perfil.'\')';
    if ($result = $con->query($sql)) {
      $empleado->id = $con->insert_id;
      $telefonos = array();
      foreach ($empleado->telefonos as $telefono) {
        $sql = 'INSERT INTO telefonos (id, numero, prefijo, pais) VALUES (DEFAULT, \''.$telefono->numero.'\', \''.$telefono->prefijo.'\', \''.$telefono->pais.'\')';
        if ($resultado = $con->query($sql)) {
          $telefono->id = $con->insert_id;
        } else {
          die();
        }
        $sql = 'INSERT INTO telefono_empleado (id_empleado, id_telefono) VALUES (\''.$empleado->id.'\', \''.$telefono->id.'\')';
        if (!$resultado = $con->query($sql)) {
          die();
        }
        array_push($telefonos, $telefono);
      }
      $empleado->telefonos = $telefonos;
      http_response_code(201);
      echo json_encode(array('empleado' => $empleado));
    } else {
      if ($con->errno == 1062) {
        http_response_code(400);
        echo json_encode(array('error' => array('code' => 400, 'message' => 'Ya existe un empleado registrado con '.$empleado->email.' como direccion de correo electronico')));
      } else {
        http_response_code(500);
        echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo guardar el empleado', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
      }
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}