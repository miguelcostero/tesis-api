<?php
require __DIR__ . '/../../config/db.php';

if (isset($_GET['query'])) {
  $sql = 'SELECT e.* FROM eventos e WHERE e.nombre LIKE \'%'.$_GET['query'].'%\'';
} else {
  $sql = 'SELECT e.* FROM eventos e';
}

if ($result = $con->query($sql)) {
  if (!$result->num_rows > 0) {
    http_response_code(200);
    echo json_encode(array('error' => array('code' => 200, 'message' => 'No se han encontrado resultados')));
    die();
  }
  $eventos = array();

  while ($e = $result->fetch_object()) {
    $evento = new stdClass();
    $evento->id = $e->id;
    $evento->nombre = $e->nombre;
    $evento->descripcion = $e->descripcion;
    $evento->fecha_agregado = $e->fecha_agregado;
    $evento->icono = $e->icono;
    $evento->invitados = $e->invitados;

    $sql = 'SELECT ei.id, ei.descripcion, ei.fecha, ei.hora, ei.notas FROM env_in ei WHERE ei.id_evento = \''.$e->id.'\'';
    if ($resultado = $con->query($sql)) {
      if ($resultado->num_rows > 0) {
        $evento->cronograma = array();
        while ($env_in = $resultado->fetch_object()) {
          array_push($evento->cronograma, $env_in);
        }
      }
    }

    $sql = 'SELECT te.* FROM tipo_evento te WHERE te.id = \''.$e->id_tipo_evento.'\'';
    if ($resultado = $con->query($sql)) {
      $tipo_evento = $resultado->fetch_object();
      $evento->tipo_evento = array(
        'id' => $tipo_evento->id,
        'nombre' => $tipo_evento->nombre
      );
    }

    $sql = 'SELECT e.* FROM empleados e WHERE e.id = \''.$e->id_empleado.'\'';
    if ($resultado = $con->query($sql)) {
      $empleado = $resultado->fetch_object();
      $evento->empleado = array(
        'id' => $empleado->id,
        'email' => $empleado->email,
        'nombre' => $empleado->nombre,
        'apellido' => $empleado->apellido
      );
    }

    $sql = 'SELECT l.* FROM locaciones l WHERE l.id = \''.$e->id_locacion.'\'';
    if ($resultado = $con->query($sql)) {
      $locacion = $resultado->fetch_object();

      $sql = 'SELECT t.* FROM telefonos t INNER JOIN telefono_locacion tl ON t.id = tl.id_telefono WHERE tl.id_locacion = \''.$locacion->id.'\'';
      if ($resultTelefono = $con->query($sql)) {
        $telefonos = array();
        while ($telefono = $resultTelefono->fetch_object()) {
          array_push($telefonos, $telefono);
        }

        $evento->locacion = array(
          'id' => $locacion->id,
          'nombre' => $locacion->nombre,
          'direccion' => $locacion->direccion,
          'capacidad' => $locacion->capacidad,
          'telefonos' => $telefonos
        );
      }
    }

    $sql = 'SELECT c.* FROM clientes c WHERE c.id = \''.$e->id_cliente.'\'';
    if ($resultado = $con->query($sql)) {
      $cliente = $resultado->fetch_object();

      $sql = 'SELECT t.* FROM telefonos t INNER JOIN telefono_cliente tc ON t.id = tc.id_telefono WHERE tc.id_cliente = \''.$cliente->id.'\'';
      if ($resultTelefono = $con->query($sql)) {
        $telefonos = array();
        while ($telefono = $resultTelefono->fetch_object()) {
          array_push($telefonos, $telefono);
        }

        $evento->cliente = array(
          'id' => $cliente->id,
          'dni' => $cliente->dni,
          'nombre' => $cliente->nombre,
          'email' => $cliente->email,
          'direccion' => $cliente->direccion,
          'telefonos' => $telefonos
        );
      }
    }

    $sql = 'SELECT ee.* FROM estado_evento ee WHERE ee.id = \''.$e->id_estado.'\'';
    if ($resultado = $con->query($sql)) {
      $estado_evento = $resultado->fetch_object();
      $evento->estado_evento = array(
        'id' => $estado_evento->id,
        'nombre' => $estado_evento->nombre
      );
    }

    array_push($eventos, $evento); 
  }

  echo json_encode($eventos);
  $result->close();
} else {
  http_response_code(500);
  echo json_encode(array('error' => array('code' => 500, 'message' => 'Error en consulta 1')));
  die();
}