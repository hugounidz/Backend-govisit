<?php
require 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

// 🔥 LIMPIAR DATOS
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');
$nombre = trim($data['nombre'] ?? '');
$apellidos = trim($data['apellidos'] ?? '');

// 🔥 VALIDAR VACÍOS
if (!$username || !$password || !$nombre || !$apellidos) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
    ]);
    exit;
}

// 🔥 VALIDAR SOLO LETRAS
if (!preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $nombre) ||
    !preg_match("/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/", $apellidos)) {

    echo json_encode([
        "success" => false,
        "message" => "Nombre y apellidos solo deben contener letras"
    ]);
    exit;
}

// 🔥 VALIDAR CONTRASEÑA
if (strlen($password) < 6) {
    echo json_encode([
        "success" => false,
        "message" => "La contraseña debe tener mínimo 6 caracteres"
    ]);
    exit;
}

// 🔥 VALIDAR USUARIO
if (!preg_match("/^[a-zA-Z0-9_]+$/", $username)) {
    echo json_encode([
        "success" => false,
        "message" => "Usuario inválido"
    ]);
    exit;
}

// 🔥 VERIFICAR SI YA EXISTE
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

// 🔐 HASH PASSWORD
$passwordHash = password_hash($password, PASSWORD_DEFAULT);

// 🔥 INSERTAR
$sql = "INSERT INTO usuarios (username, password, nombre, apellidos)
        VALUES (?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
$stmt->execute([$username, $passwordHash, $nombre, $apellidos]);

echo json_encode([
    "success" => true,
    "message" => "Usuario creado correctamente"
]);