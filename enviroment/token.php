<?php

class Token {
  protected $secret_key;

  function __construct () {
    $this->secret_key = md5('clave_super_secreta');
  }

  function encode ($data) {
    $issuedAt = time();
    $tokenId = base64_encode(mcrypt_create_iv(32));

    $token = array(
      'iat' => $issuedAt,
      'jti'  => $tokenId,
      'nbf'  => $issuedAt + 10,
      'exp'  => $issuedAt + (30 * 24 * 60 * 60),
      'data' => $data
    );

    return \Firebase\JWT\JWT::encode($token, $this->secret_key, 'HS512');
  }

  function decode ($token) {
    return \Firebase\JWT\JWT::decode($token, $this->secret_key, array('HS512'));
  }
}