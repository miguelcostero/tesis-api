<?php

require __DIR__ . '/../../config/db.php';
require __DIR__ . '/../../enviroment/email.php';

if ($body = json_decode(file_get_contents('php://input'))) {

  $sql = 'SELECT e.id AS id, CONCAT(e.nombre, \' \', e.apellido) AS nombre FROM empleados e WHERE e.email = \''.$body->email.'\'';
  if ($result = $con->query($sql)) {
    if ($result->num_rows > 0) {
      $data = $result->fetch_object();
      $password = randomPassword();

      $contact = new stdClass();
      $contact->email = $body->email;
      $contact->name = $data->nombre;

      $dataEmail = new stdClass();
      $dataEmail->address = $contact;
      $dataEmail->subject = 'Recuperación de contraseña';
      $dataEmail->body = '
        <h3>Nueva contraseña generada</h3>
        <p>Estimado(a) '.$data->nombre.':</p>
        <p>Esta es su nueva contraseña de acceso a la aplicación: <b>'.$password.'</b></p>
        <p>Si no ha sido usted, por favor no dude en contactarnos a: admin@talentproducciones.com.ve</p>
        <p>-------------------------</p>
        <p>El equipo de Talent Producciones, C.A.</p>';
      $dataEmail->altbody = 'Su nueva contraseña es: '.$password;

      $resultado = sendEmail($dataEmail);

      if ($resultado->status == 'success') {
        $sql = 'UPDATE empleados e SET e.password = MD5(\''.$password.'\') WHERE e.id = \''.$data->id.'\'';
        if ($result = $con->query($sql)) {
          http_response_code(200);
          echo json_encode(array('status' => 'success', 'message' => 'Se ha enviado un email a '. $body->email .' con su nueva contraseña de acceso'));
        } else {
          http_response_code(500);
          echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido actualizar la bd.', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
        }
      } else {
        http_response_code(500);
        echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido procesar su solicitud.', 'details' => $resultado->message)));
      }
    } else {
      http_response_code(400);
      echo json_encode(array('error' => array('code' => 400, 'message' => 'No existe ningún empleado asociado a esta direccion de correo electrónico')));
    }
  } else {
    http_response_code(500);
    echo json_encode(array('error' => array('code' => 500, 'message' => 'No se ha podido proceder con la petición', 'mysql_errno' => $con->errno, 'mysql_error' => $con->error)));
  }
} else {
  http_response_code(400);
  echo json_encode(array('error' => array('code' => 400, 'message' => 'No se ha proporcionado un email válido para continuar')));
}

function randomPassword() {
  $alphabet = 'abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789!#$%&/()=@{}[]*+-';
  $pass = array();
  $alphaLength = strlen($alphabet) - 1;
  for ($i = 0; $i < 12; $i++) {
    $n = rand(0, $alphaLength);
    $pass[] = $alphabet[$n];
  }
  return implode($pass);
}