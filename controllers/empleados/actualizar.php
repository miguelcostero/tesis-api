<?php
require __DIR__ . '/../../config/db.php';

if ($empleado = json_decode(file_get_contents('php://input'))) {
  if (
    isset($empleado->email) &&
    isset($empleado->nombre) &&
    isset($empleado->apellido) &&
    isset($empleado->fecha_nacimiento) &&
    isset($empleado->img_perfil) &&
    isset($empleado->role) &&
    isset($empleado->telefonos)
  ) {
    $empleado->id = $id;
    $sql = 'UPDATE empleados e SET e.email = \''.$empleado->email.'\', e.nombre = \''.$empleado->nombre.'\', e.apellido = \''.$empleado->apellido.'\', e.fecha_nacimiento = \''.$empleado->fecha_nacimiento.'\', e.img_perfil = \''.$empleado->img_perfil.'\', e.role = \''.$empleado->role->id.'\' WHERE e.id = \''.$empleado->id.'\'';
    if ($result = $con->query($sql)) {
      foreach ($empleado->telefonos as $telefono) {
        $sql = 'UPDATE telefonos t INNER JOIN telefono_empleado te ON t.id = te.id_telefono SET t.prefijo = \''.$telefono->prefijo.'\', t.numero = \''.$telefono->numero.'\', t.pais = \''.$telefono->pais.'\' WHERE te.id_empleado = \''.$empleado->id.'\' AND t.id = \''.$telefono->id.'\'';
        if (!$resultado = $con->query($sql)) {
          http_response_code(500);
          echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar numero de telefono', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
          die();
        }
      }
      http_response_code(200);
      echo json_encode(array('empleado' => $empleado));
    } else {
      http_response_code(500);
      echo json_encode(array('error' => array('code' => 500, 'message' => 'No se pudo actualizar el empleado', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion esta incompleto')));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'Cuerpo de la peticion es inexistente')));
}