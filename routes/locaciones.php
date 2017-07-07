<?php

$router->map('GET', '/v1/locaciones/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/locaciones.php';
});

$router->map('GET', '/v1/locaciones/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/detalles.php';
});

$router->map('POST', '/v1/locaciones/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/agregar.php';
});

$router->map('PUT', '/v1/locaciones/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/actualizar.php';
});

$router->map('DELETE', '/v1/locaciones/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/eliminar.php';
});