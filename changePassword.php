<?php
require 'config.php';

header('Content-Type: application/json');

// 📥 Datos
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$actual = $data['actual'] ?? '';
$nueva = $data['nueva'] ?? '';

// 🧠 Validación básica
if (!$id || !$actual || !$nueva) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
    ]);
    exit;
}

try {

    // 🔍 Obtener contraseña actual (HASH)
    $sql = "SELECT password FROM usuarios WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);

    $usuario = $stmt->fetch();

    if (!$usuario) {
        echo json_encode([
            "success" => false,
            "message" => "Usuario no encontrado"
        ]);
        exit;
    }

    // 🔐 VALIDAR PASSWORD ACTUAL (CORRECTO)
    if (!password_verify($actual, $usuario['password'])) {
        echo json_encode([
            "success" => false,
            "message" => "Contraseña actual incorrecta"
        ]);
        exit;
    }

    // 🔥 nueva != actual
    if ($actual === $nueva) {
        echo json_encode([
            "success" => false,
            "message" => "La nueva contraseña no puede ser igual a la actual"
        ]);
        exit;
    }

    // 🔒 validar longitud
    if (strlen($nueva) < 8) {
        echo json_encode([
            "success" => false,
            "message" => "La contraseña debe tener al menos 8 caracteres"
        ]);
        exit;
    }

    // 🔐 HASH NUEVA CONTRASEÑA
    $nuevaHash = password_hash($nueva, PASSWORD_DEFAULT);

    // 🧱 Actualizar contraseña
    $sql = "UPDATE usuarios SET password = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nuevaHash, $id]);

    echo json_encode([
        "success" => true,
        "message" => "Contraseña actualizada correctamente"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error en el servidor",
        "error" => $e->getMessage()
    ]);
}