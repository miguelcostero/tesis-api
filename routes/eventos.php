<?php

$router->map('GET', '/v1/eventos/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/eventos/eventos.php';
});

$router->map('GET', '/v1/eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/eventos/detalles.php';
});

$router->map('POST', '/v1/eventos/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/eventos/agregar.php';
});

$router->map('PUT', '/v1/eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/eventos/actualizar.php';
});

$router->map('DELETE', '/v1/admin/eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/eventos/eliminar.php';
});