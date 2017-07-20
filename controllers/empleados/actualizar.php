<?php
require __DIR__ . '/../../config/db.php';

if ($body = json_decode(file_get_contents('php://input'))) {
  if (
    isset($body->empleado->email) &&
    isset($body->empleado->nombre) &&
    isset($body->empleado->apellido) &&
    isset($body->empleado->fecha_nacimiento) &&
    isset($body->empleado->img_perfil) &&
    isset($body->empleado->telefonos) &&
    isset($body->password)
  ) {
    $empleado = $body->empleado;
    $empleado->id = $id;

    $sql = 'SELECT e.id FROM empleados e WHERE e.email = \''.$empleado->email.'\' AND e.password = \''.$body->password.'\'';
    if ($con->query($sql)) {
      if (isset($empleado->password)) {
        $sql = 'UPDATE empleados e SET e.email = \''.$empleado->email.'\', e.password = \''.$empleado->password.'\', e.nombre = \''.$empleado->nombre.'\', e.apellido = \''.$empleado->apellido.'\', e.fecha_nacimiento = \''.$empleado->fecha_nacimiento.'\', e.img_perfil = \''.$empleado->img_perfil.'\' WHERE e.id = \''.$empleado->id.'\'';
      } else {
        $sql = 'UPDATE empleados e SET e.email = \''.$empleado->email.'\', e.nombre = \''.$empleado->nombre.'\', e.apellido = \''.$empleado->apellido.'\', e.fecha_nacimiento = \''.$empleado->fecha_nacimiento.'\', e.img_perfil = \''.$empleado->img_perfil.'\' WHERE e.id = \''.$empleado->id.'\'';
      }
      if ($con->query($sql)) {
        foreach ($empleado->telefonos as $telefono) {
          if (isset($telefono->id)) {
            $sql = 'UPDATE telefonos t INNER JOIN telefono_empleado te ON t.id = te.id_telefono SET t.prefijo = \''.$telefono->prefijo.'\', t.numero = \''.$telefono->numero.'\', t.pais = \''.$telefono->pais.'\' WHERE te.id_empleado = \''.$empleado->id.'\' AND t.id = \''.$telefono->id.'\'';
            if (!$con->query($sql)) {
              http_response_code(500);
              echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar numero de telefono', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
              die();
            }
          } else {
            $sql = 'INSERT INTO telefonos (id, numero, prefijo, pais) VALUES (DEFAULT, \''.$telefono->numero.'\', \''.$telefono->prefijo.'\', \''.$telefono->pais.'\')';
            if ($con->query($sql)) {
              $id_telefono = $con->insert_id;

              $sql ='INSERT INTO telefono_empleado (id_empleado, id_telefono) VALUES (\''.$empleado->id.'\', \''.$id_telefono.'\')';
              if (!$con->query($sql)) {
                http_response_code(500);
                echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo crear el numero de telefono', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
                die();
              }

            } else {
              http_response_code(500);
              echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo crear el numero de telefono', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
              die();
            }
          }
        }

        http_response_code(200);
      } else {
        http_response_code(500);
        echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar el empleado', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
      }
    } else {
      http_response_code(400);
      echo json_encode(array('error' => array('code' => 400, 'message' => 'Contraseña incorrecta')));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}