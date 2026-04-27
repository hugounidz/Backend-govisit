<?php
require 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? null;

$sqlUser = "SELECT nombre FROM usuarios WHERE id = ?";
$stmtUser = $pdo->prepare($sqlUser);
$stmtUser->execute([$user_id]);

$usuario = $stmtUser->fetch();

if (!$usuario) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario no encontrado"
    ]);
    exit;
}

if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "Falta user_id"
    ]);
    exit;
}

$sql = "SELECT tipo FROM registros 
        WHERE user_id = ? 
        ORDER BY fecha DESC 
        LIMIT 1";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

$ultimo = $stmt->fetch();

// 🔥 Decidir tipo
if (!$ultimo || $ultimo['tipo'] === 'salida') {
    $tipo = 'entrada';
} else {
    $tipo = 'salida';
}

// 🧱 Insertar nuevo registro
$sql = "INSERT INTO registros (user_id, tipo) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id, $tipo]);

echo json_encode([
    "success" => true,
    "tipo" => $tipo,
    "nombre" => $usuario['nombre'],
    "message" => "Registro guardado"
]);