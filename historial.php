<?php
require 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? null;

if (!$user_id) {
    echo json_encode([
        "success" => false,
        "message" => "Falta user_id"
    ]);
    exit;
}

$sql = "SELECT tipo, fecha 
        FROM registros 
        WHERE user_id = ? 
        ORDER BY fecha DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute([$user_id]);

$registros = $stmt->fetchAll();

echo json_encode([
    "success" => true,
    "data" => $registros
]);