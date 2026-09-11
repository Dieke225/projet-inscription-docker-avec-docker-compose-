<?php
// ✅ Nettoyage des headers CORS obsolètes (Nginx unifie tout sur le port 80, évitant le Cross-Origin)
header("Content-Type: application/json; charset=UTF-8"); 

ini_set('display_errors', 0);
error_reporting(0);

$host = getenv('DB_HOST') ?: 'inscription-db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'root';
$db   = getenv('DB_NAME') ?: 'inscriptions';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Erreur de connexion à la base"]);
    exit;
}

$result = $conn->query("SELECT id, nom, email, created_at FROM users ORDER BY id DESC");
$rows = [];

while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);
?>
