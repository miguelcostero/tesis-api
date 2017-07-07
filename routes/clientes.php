<?php

$router->map('GET', '/v1/clientes/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/clientes/clientes.php';
});

$router->map('GET', '/v1/clientes/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/clientes/detalles.php';
});

$router->map('POST', '/v1/clientes/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/clientes/agregar.php';
});

$router->map('PUT', '/v1/clientes/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/clientes/actualizar.php';
});

$router->map('DELETE', '/v1/clientes/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/clientes/eliminar.php';
});