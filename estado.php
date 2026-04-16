<?php
require 'config.php';

header('Content-Type: application/json');

// 📥 Datos
$data = json_decode(file_get_contents("php://input"), true);
$user_id = $data['user_id'] ?? null;

// 🧠 Validación
if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "Falta user_id"
    ]);
    exit;
}

try {

    // 🔍 Último registro del usuario
    $sql = "SELECT tipo, fecha 
            FROM registros 
            WHERE user_id = ? 
            ORDER BY fecha DESC 
            LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);

    $registro = $stmt->fetch();

    // 🧠 Lógica de estado
    $estado = "fuera";
    $fecha = null;

    if ($registro) {
        $estado = $registro['tipo'] === 'entrada' ? 'dentro' : 'fuera';
        $fecha = $registro['fecha'];
    }

    echo json_encode([
        "success" => true,
        "estado" => $estado,
        "fecha" => $fecha
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor",
        "error" => $e->getMessage()
    ]);
}