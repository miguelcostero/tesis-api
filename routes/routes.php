<?php

require __DIR__ . '/../vendor/autoload.php';

$router = new AltoRouter();
$_SERVER['SERVER_NAME'] == 'localhost' ? $router->setBasePath('/tesis/api') : $router->setBasePath('');

# map homepage
$router->map('GET', '/', function () {
	require __DIR__ . '/../controllers/home.php';
});

# ================================================================= #
# -----------------------INICIO RUTAS EVENTOS---------------------- #
# ================================================================= #
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
# ================================================================= #
# ----------------------FIN RUTAS EVENTOS-------------------------- #
# ================================================================= #


# ================================================================= #
# --------------------INICIO RUTAS CLIENTES------------------------ #
# ================================================================= #
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
# ================================================================= #
# ------------------------FIN RUTAS CLIENTES------------------------#
# ================================================================= #

// match current request url
$match = $router->match();

// call closure or throw 404 status
if( $match && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] ); 
} else {
	header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
	echo json_encode(array('error' => array('code' => 404, 'message' => 'Recurso no encontrado')));
}