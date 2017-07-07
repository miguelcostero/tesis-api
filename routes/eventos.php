<?php

$router->map('GET', '/eventos/', function () {
	require __DIR__ . '/../controllers/eventos/eventos.php';
});

$router->map('GET', '/eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/eventos/detalles.php';
});

$router->map('POST', '/eventos/', function () {
	require __DIR__ . '/../controllers/eventos/agregar.php';
});

$router->map('PUT', '/eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/eventos/actualizar.php';
});

$router->map('DELETE', '/eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/eventos/eliminar.php';
});