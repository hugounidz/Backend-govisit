<?php
// 1. Importar el archivo de configuración
header("Content-Type: application/json; charset=UTF-8");
require_once 'config.php';

// Supongamos que recibes datos de un formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // 1. Leer el flujo de entrada (input stream)
  $json = file_get_contents("php://input");

  // 2. Decodificar el JSON a un array asociativo
  $datos = json_decode($json, true);

  // 3. Preparar la estructura de la respuesta
  $respuesta = [
    "status" => "error",
    "mensaje" => "Ocurrió un error inesperado",
    "data" => null
  ];

  $estatus = $datos['estatus'] ?? '';
  $fecha_ingreso  = $datos['fecha_ingreso'] ?? '';
  $tipo = $datos['tipo'] ?? '';
  $nombres = $datos['nombres'] ?? '';
  $apellidos = $datos['apellidos'] ?? '';
  $fecha_salida = $datos['fecha_salida'] ?? '';
  $motivo = $datos['motivo'] ?? '';
  $fk_usuario = $datos['fk_usuario'] ?? null;

  // 2. Preparar la sentencia SQL con marcadores (evita SQL Injection)
  $sql = "INSERT INTO historial (estatus, fecha_ingreso, tipo, nombres, apellidos, fecha_salida, motivo, fk_usuario ) VALUES (:estatus, :fecha_ingreso, :tipo, :nombres, :apellidos, :fecha_salida, :motivo, :fk_usuario )";
  
  try {
    $stmt = $pdo->prepare($sql);
    
    // 3. Ejecutar pasando los valores
    $stmt->execute([
      'estatus' => $estatus,
      'fecha_ingreso'  => $fecha_ingreso,
      'tipo'  => $tipo,
      'nombres'  => $nombres,
      'apellidos'  => $apellidos,
      'fecha_salida'  => $fecha_salida,
      'motivo'  => $motivo,
      'fk_usuario'  => $fk_usuario
    ]);

    http_response_code(200); // Código HTTP: Creado
    $respuesta["status"] = "success";
    $respuesta["mensaje"] = "Registro creado correctamente";
    $respuesta["data"] = [
        "id" => $pdo->lastInsertId(),
        "nombre" => $nombres
    ];

  } catch (Exception $e) {
    http_response_code(500); // Código HTTP: Error de servidor
    $respuesta["mensaje"] = "Error en la base de datos: " . $e->getMessage();
  }
  // 5. Imprimir la respuesta final en JSON
  echo json_encode($respuesta);
}else{
  echo "No es metodo POST";
}
?>