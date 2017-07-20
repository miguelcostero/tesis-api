<?php

$router->map('DELETE', '/v1/telefonos/empleado/[i:id]/', function ($id) {
  require __DIR__ . '/../enviroment/authentification.php';
  $tabla = 'telefono_empleado';
	require __DIR__ . '/../controllers/telefonos/eliminar.php';
});

$router->map('DELETE', '/v1/telefonos/cliente/[i:id]/', function ($id) {
  require __DIR__ . '/../enviroment/authentification.php';
  $tabla = 'telefono_cliente';
	require __DIR__ . '/../controllers/telefonos/eliminar.php';
});

$router->map('DELETE', '/v1/telefonos/locacion/[i:id]/', function ($id) {
  require __DIR__ . '/../enviroment/authentification.php';
  $tabla = 'telefono_locacion';
	require __DIR__ . '/../controllers/telefonos/eliminar.php';
});

$router->map('DELETE', '/v1/telefonos/talento/[i:id]/', function ($id) {
  require __DIR__ . '/../enviroment/authentification.php';
  $tabla = 'telefono_talento';
	require __DIR__ . '/../controllers/telefonos/eliminar.php';
});