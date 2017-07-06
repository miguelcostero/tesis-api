<?php
require __DIR__ . '/../../config/db.php';

if ($getClientes = $con->query('CALL getClientes()')) {
  $clientes = array();
  $telefonos = array();
  if (!$getClientes->num_rows > 0) {
    echo json_encode(array(
      'error' => array(
        'code' => 400,
        'message' => 'No se encontraron resultados a su busqueda'
      )
    ));
    # Liberar y cerrar conexion
    $getClientes->free();
    $con->close();
    die();
  }
  $clientesDb = $getClientes->fetch_all(MYSQLI_ASSOC);

  $clientesB = array();
  foreach ($clientesDb as $cliente) {
    $b = array(
      'id' => $cliente['id_cliente'],
      'dni' => $cliente['dni'],
      'nombre' => $cliente['nombre'],
      'email' => $cliente['email'],
      'direccion' => $cliente['direccion'],
    );

    $telefono = array(
      'id_cliente' => $cliente['id_cliente'],
      'id' => $cliente['id_telefono'],
      'numero' => $cliente['numero'],
      'prefijo' => $cliente['prefijo'],
      'pais' => $cliente['pais']
    );

    if (!in_array($b, $clientesB)) {
      array_push($clientesB, $b);      
    }
    array_push($telefonos, $telefono);    
  }

  foreach ($clientesB as $cliente) {
    $c = $cliente;
    $c['telefonos'] = array();
    foreach ($telefonos as $telefono) { 
      if ($telefono['id_cliente'] === $cliente['id']) {
        array_push($c['telefonos'], array(
          'id' => $telefono['id'],
          'numero' => $telefono['numero'],
          'prefijo' => $telefono['prefijo'],
          'pais' => $telefono['pais']
        ));
      }
    }
    array_push($clientes, $c);
  }

  echo json_encode($clientes);

  # Liberar y cerrar conexion
  $getClientes->free();
  $con->close();
} else {
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Error en consulta 1')));
  die();
}