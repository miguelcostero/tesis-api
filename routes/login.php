<?php

$router->map('POST', '/login/', function () {
	require __DIR__ . '/../controllers/login/login.php';
});