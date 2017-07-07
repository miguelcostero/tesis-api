<?php

$router->map('POST', '/v1/login/', function () {
	require __DIR__ . '/../controllers/login/login.php';
});