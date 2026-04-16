<?php
require 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);

$user = $stmt->fetch();

if (!$user || $user['password'] !== $password) {
    echo json_encode([
        "success" => false,
        "message" => "Credenciales incorrectas"
    ]);
    exit;
}

echo json_encode([
    "success" => true,
    "user" => [
        "id" => $user['id'],
        "nombre" => $user['nombre'],
        "apellidos" => $user['apellidos'],
        "username" => $user['username']
    ]
]);