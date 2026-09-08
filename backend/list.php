<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

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

$result = $conn->query("SELECT id, nom, email, date_inscription FROM users ORDER BY id DESC");
$rows = [];

while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
}

echo json_encode($rows);
?>
