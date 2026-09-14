<?php
// ✅ Activation TRÈS STRICTE des erreurs pour le débogage local
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Content-Type: application/json; charset=UTF-8"); 

$host = getenv('DB_HOST') ?: 'inscription-db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'root';
$db   = getenv('DB_NAME') ?: 'inscriptions';

try {
    // Activer le mode d'exception pour mysqli afin d'attraper les erreurs SQL dans le catch
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    
    $conn = new mysqli($host, $user, $pass, $db);

    $result = $conn->query("SELECT id, nom, email, created_at FROM users ORDER BY id DESC");
    $rows = [];

    while ($row = $result->fetch_assoc()) {
        $rows[] = $row;
    }

    echo json_encode($rows);
    
    $result->free();
    $conn->close();

} catch (Exception $e) {
    // ✅ Si le code plante, on renvoie le message d'erreur EXACT au format texte
    http_response_code(500);
    echo json_encode([
        "error" => true,
        "message" => $e->getMessage(),
        "file" => $e->getFile(),
        "line" => $e->getLine()
    ], JSON_PRETTY_PRINT);
    exit;
}
?>
