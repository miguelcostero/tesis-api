<?php

$router->map('GET', '/v1/tipo-eventos/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/tipo-eventos/tipo-eventos.php';
});

$router->map('GET', '/v1/tipo-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/tipo-eventos/detalles.php';
});

$router->map('POST', '/v1/tipo-eventos/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/tipo-eventos/agregar.php';
});

$router->map('PUT', '/v1/tipo-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/tipo-eventos/actualizar.php';
});

$router->map('DELETE', '/v1/tipo-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/tipo-eventos/eliminar.php';
});