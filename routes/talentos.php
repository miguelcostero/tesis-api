<?php

$router->map('GET', '/v1/talentos/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/talentos/talentos.php';
});

$router->map('GET', '/v1/talentos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/talentos/detalles.php';
});

$router->map('POST', '/v1/talentos/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/talentos/agregar.php';
});

$router->map('PUT', '/v1/talentos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/talentos/actualizar.php';
});

$router->map('DELETE', '/v1/talentos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/talentos/eliminar.php';
});