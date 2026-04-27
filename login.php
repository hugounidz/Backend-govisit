<?php
require 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';

// 🔥 VALIDAR
if (!$username || !$password) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
    ]);
    exit;
}

// 🔍 BUSCAR USUARIO
$sql = "SELECT id, username, password, nombre, apellidos 
        FROM usuarios 
        WHERE username = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);

$user = $stmt->fetch();

if (!$user) {
    echo json_encode([
        "success" => false,
        "message" => "Credenciales incorrectas"
    ]);
    exit;
}

// 🔐 VERIFICAR PASSWORD
if (!password_verify($password, $user['password'])) {
    echo json_encode([
        "success" => false,
        "message" => "Credenciales incorrectas"
    ]);
    exit;
}

// 🔥 LOGIN OK
echo json_encode([
    "success" => true,
    "user" => [
        "id" => $user['id'],
        "nombre" => $user['nombre'],
        "apellidos" => $user['apellidos'],
        "username" => $user['username']
    ]
]);