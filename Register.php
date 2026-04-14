<?php
require 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$username = $data['username'] ?? '';
$password = $data['password'] ?? '';
$nombre = $data['nombre'] ?? '';
$apellidos = $data['apellidos'] ?? '';

// validar
if (!$username || !$password || !$nombre || !$apellidos) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
    ]);
    exit;
}

// verificar si ya existe
$sql = "SELECT id FROM usuarios WHERE username = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$username]);

if ($stmt->fetch()) {
    echo json_encode([
        "success" => false,
        "message" => "El usuario ya existe"
    ]);
    exit;
}

// insertar usuario
$sql = "INSERT INTO usuarios (username, password, nombre, apellidos)
        VALUES (?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $password, $nombre, $apellidos]);

echo json_encode([
    "success" => true,
    "message" => "Usuario creado correctamente"
]);