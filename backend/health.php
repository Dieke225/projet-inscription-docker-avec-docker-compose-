<?php
header("Content-Type: application/json; charset=UTF-8");

$host = getenv('DB_HOST') ?: 'inscription-db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'root';
$db   = getenv('DB_NAME') ?: 'inscriptions';

$conn = @new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "db" => "unreachable",
        "php_version" => PHP_VERSION,
        "mysql_version" => null
    ]);
    exit;
}

http_response_code(200);
echo json_encode([
    "status" => "ok",
    "db" => "connected",
    "php_version" => PHP_VERSION,
    "mysql_version" => $conn->server_info
]);
?>
