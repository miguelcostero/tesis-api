<?php

$router->map('GET', '/locaciones/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/locaciones.php';
});

$router->map('GET', '/locaciones/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/detalles.php';
});

$router->map('POST', '/locaciones/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/agregar.php';
});

$router->map('PUT', '/locaciones/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/actualizar.php';
});

$router->map('DELETE', '/locaciones/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/locaciones/eliminar.php';
});