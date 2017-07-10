<?php

$router->map('POST', '/v1/login/', function () {
	require __DIR__ . '/../controllers/login/login.php';
});

$router->map('POST', '/v1/reset-password/', function () {
	require __DIR__ . '/../controllers/login/reset-password.php';
});

$router->map('POST', '/v1/validate-token/', function () {
	require __DIR__ . '/../controllers/login/validate-token.php';
});