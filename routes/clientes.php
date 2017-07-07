<?php

$router->map('GET', '/clientes/', function () {
	require __DIR__ . '/../controllers/clientes/clientes.php';
});

$router->map('GET', '/clientes/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/clientes/detalles.php';
});

$router->map('POST', '/clientes/', function () {
	require __DIR__ . '/../controllers/clientes/agregar.php';
});

$router->map('PUT', '/clientes/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/clientes/actualizar.php';
});

$router->map('DELETE', '/clientes/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/clientes/eliminar.php';
});