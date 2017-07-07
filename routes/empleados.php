<?php

$router->map('GET', '/empleados/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/empleados.php';
});

$router->map('GET', '/empleados/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/detalles.php';
});

$router->map('POST', '/empleados/', function () {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/agregar.php';
});

$router->map('PUT', '/empleados/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/actualizar.php';
});

$router->map('DELETE', '/admin/empleados/[i:id]/', function ($id) {
	require __DIR__ . '/../enviroment/authentification.php';
	require __DIR__ . '/../controllers/empleados/eliminar.php';
});