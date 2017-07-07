<?php
require __DIR__ . '/token.php';
$token = new Token;
$headers = getallheaders();

if (isset($headers['Access-Token'])) {
  $access_token = $headers['Access-Token'];
  try {
    $decoded = $token->decode($access_token);
    if ($decoded->exp < time()) {
      http_response_code(401);
      echo json_encode(array('error' => array('code' => 401, 'message' => 'Token de acceso está expirado')));
      die();
    }

    $request_uri = explode('/', trim($_SERVER['REQUEST_URI']));
    if (in_array('admin', $request_uri)) {
      if (!$decoded->data->role == 2 || !$decoded->data->role == 3) {
        http_response_code(401);
        echo json_encode(array('error' => array('code' => 401, 'message' => 'Usted no posee permisos para acceder a este recurso')));
        die();
      }
    }
  } catch (Exception $e) {
    http_response_code(401);
    echo json_encode(array('error' => array('code' => 401, 'message' => 'Token de acceso inválido', 'err_message' => $e->getMessage(), 'err_code' => $e->getCode())));
    die();
  }
} else {
  http_response_code(401);
  echo json_encode(array('error' => array('code' => 401, 'message' => 'No existe token de acceso en la petición')));
  die();
}