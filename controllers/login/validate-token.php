<?php
require __DIR__ . '/../../enviroment/token.php';
$token = new Token;

if ($body = json_decode(file_get_contents('php://input'))) {
  if ($body->token) {
    try {
      $decoded = $token->decode($body->token);

      if ($decoded->exp < time()) {
        http_response_code(401);
        echo json_encode(array('error' => array('code' => 401, 'message' => 'Token de acceso está expirado')));
        die();
      } else {
        http_response_code(200);
        echo json_encode(array('message' => 'Token validdo hasta '.$decoded->exp));
      }

    } catch (Exception $e) {
      http_response_code(401);
      echo json_encode(array('error' => array('code' => 401, 'message' => 'Token de acceso inválido', 'err_message' => $e->getMessage(), 'err_code' => $e->getCode())));
    }
  } else {
    http_response_code(400);
    echo json_encode(array('error' => array('code' => 400, 'message' => 'Token inexistente en la petición')));
  }
} else {
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Cuerpo de la peticion incompleto')));
}