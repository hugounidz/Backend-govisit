<?php
/**
 * 1. Configuración de CORS
 * Esto debe ir antes de cualquier salida de texto o lógica de BD
 */
header("Access-Control-Allow-Origin: *"); // Permite cualquier origen (ajusta en producción)
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

// Responder a la petición de verificación (Preflight) y detener la ejecución
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit;
}

/**
 * 2. Configuración de la base de datos
 */
$host    = 'localhost';
$db_name = 'govisit';
$user    = 'root';
$pass    = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db_name;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    // En APIs, es mejor devolver un JSON de error que un Throw crudo
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode(["error" => "Error de conexión: " . $e->getMessage()]);
    exit;
}
?>