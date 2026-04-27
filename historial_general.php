<?php
require 'config.php';

header('Content-Type: application/json');

try {

    $sql = "SELECT r.user_id, r.tipo, r.fecha, 
                  CONCAT(u.nombre, ' ', u.apellidos) AS nombre
            FROM registros r
            JOIN usuarios u ON r.user_id = u.id
            ORDER BY r.fecha DESC
            LIMIT 50";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $registros = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "registros" => $registros
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor",
        "error" => $e->getMessage()
    ]);
}