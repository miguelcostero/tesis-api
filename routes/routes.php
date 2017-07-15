<?php

require __DIR__ . '/../vendor/autoload.php';

$router = new AltoRouter();
$_SERVER['SERVER_NAME'] == 'localhost' ? $router->setBasePath('/tesis/api') : $router->setBasePath('');

# map homepage
$router->map('GET', '/', function () {
	require __DIR__ . '/../controllers/home.php';
});

// Rutas eventos
require __DIR__ . '/eventos.php';

// Rutas clientes
require __DIR__ . '/clientes.php';

// Rutas locaciones
require __DIR__ . '/locaciones.php';

// Rutas empleados
require __DIR__ . '/empleados.php';

// Rutas login
require __DIR__ . '/login.php';

// Rutas login
require __DIR__ . '/estado-eventos.php';

// Rutas login
require __DIR__ . '/tipo-eventos.php';

// match current request url
$match = $router->match();

// call closure or throw 404 status
if( $match && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
	echo json_encode(array('error' => array('code' => 404, 'message' => 'Recurso no encontrado')));
}