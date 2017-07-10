<?php
require __DIR__ . '/../../config/db.php';
require __DIR__ . '/../../enviroment/token.php';

$token = new Token;

if ($login = json_decode(file_get_contents('php://input'))) {
  if (isset($login->email) && isset($login->password)) {

    $sql = 'SELECT e.* FROM empleados e WHERE e.email = \''.$login->email.'\'';
    if ($result = $con->query($sql)) {
      if (!$result->num_rows > 0) {
        http_response_code(401);
        echo json_encode(array('error' => array('code' => 401, 'message' => 'No existe un empleado con '.$login->email.' como correo electronico')));
        die();
      }

      $login_db = $result->fetch_object();
      if ($login->password === $login_db->password) {
        $empleado = new stdClass();
        $empleado->email = $login_db->email;
        $empleado->nombre = $login_db->nombre;
        $empleado->apellido = $login_db->apellido;
        $empleado->fecha_nacimiento = $login_db->fecha_nacimiento;
        $empleado->img_perfil = $login_db->img_perfil;    

        $sql = 'SELECT r.* FROM roles r WHERE r.id = \''.$login_db->role.'\'';
        if ($resultado = $con->query($sql)) {
          if ($resultado->num_rows > 0) {
            $empleado->role = $resultado->fetch_object();
          }
        }

        $sql = 'SELECT t.* FROM telefonos t INNER JOIN telefono_empleado te ON t.id = te.id_telefono WHERE te.id_empleado = \''.$empleado->id.'\'';
        if ($resultado = $con->query($sql)) {
          if ($resultado->num_rows > 0) {
            $telefonos = array();
            while ($telefono = $resultado->fetch_object()) {
              array_push($telefonos, $telefono);
            }
            $empleado->telefonos = $telefonos;
          }
        }

        $empleado->token = $token->encode(array('id' => $login_db->id, 'email' => $login_db->email, 'role' => $empleado->role->id));

        http_response_code(200);
        echo json_encode($empleado);
      } else {
        http_response_code(401);
        echo json_encode(array('error' => array('code' => 401, 'message' => 'Contraseña incorrecta')));
      }
    } else {
      http_response_code(401);
      echo json_encode(array('error' => array('code' => 401, 'message' => 'Ha ocurrido un error desconocido.', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
    }
  } else {
    http_response_code(401);
    echo json_encode(array('error' => array('code' => 401, 'message' => 'Email o contraeña inexistente')));
  }
} else {
  http_response_code(401);
  echo json_encode(array('error' => array('code' => 401, 'message' => 'Cuerpo de petición inexistente')));
}
