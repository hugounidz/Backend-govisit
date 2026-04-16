<?php
require 'config.php';

header('Content-Type: application/json');

// 📥 Obtener datos
$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$nombre = $data['nombre'] ?? '';
$apellidos = $data['apellidos'] ?? '';

// 🧠 Validación
if (!$id || !$nombre || !$apellidos) {
    echo json_encode([
        "success" => false,
        "message" => "Faltan datos"
    ]);
    exit;
}

try {
    // 🧱 Actualizar usuario
    $sql = "UPDATE usuarios SET nombre = ?, apellidos = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$nombre, $apellidos, $id]);

    echo json_encode([
        "success" => true,
        "message" => "Perfil actualizado correctamente"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Error al actualizar",
        "error" => $e->getMessage()
    ]);
}