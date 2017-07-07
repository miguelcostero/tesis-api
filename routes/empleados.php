<?php

$router->map('GET', '/v1/empleados/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/empleados.php';
});

$router->map('GET', '/v1/empleados/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/detalles.php';
});

$router->map('POST', '/v1/empleados/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/agregar.php';
});

$router->map('PUT', '/v1/empleados/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/actualizar.php';
});

$router->map('DELETE', '/v1/admin/empleados/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/eliminar.php';
});