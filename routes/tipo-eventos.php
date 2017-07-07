<?php

$router->map('GET', '/tipo-eventos/', function () {
	require __DIR__ . '/../controllers/tipo-eventos/tipo-eventos.php';
});

$router->map('GET', '/tipo-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/tipo-eventos/detalles.php';
});

$router->map('POST', '/tipo-eventos/', function () {
	require __DIR__ . '/../controllers/tipo-eventos/agregar.php';
});

$router->map('PUT', '/tipo-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/tipo-eventos/actualizar.php';
});

$router->map('DELETE', '/tipo-eventos/[i:id]/', function ($id) {
	require __DIR__ . '/../controllers/tipo-eventos/eliminar.php';
});