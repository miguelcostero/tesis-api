<?php

$router->map('GET', '/v1/estado-eventos/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/estado-eventos/estado-eventos.php';
});

$router->map('GET', '/v1/estado-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/estado-eventos/detalles.php';
});

$router->map('POST', '/v1/estado-eventos/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/estado-eventos/agregar.php';
});

$router->map('PUT', '/v1/estado-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/estado-eventos/actualizar.php';
});

$router->map('DELETE', '/v1/estado-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/estado-eventos/eliminar.php';
});