<?php
header("Access-Control-Allow-Origin: http://localhost:8085");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");

ini_set('display_errors', 0);
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// --- ROUTE HEALTH ---
if (strpos($_SERVER['REQUEST_URI'], '/health') !== false) {
    $host = getenv('DB_HOST') ?: 'inscription-db';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: 'root';
    $db   = getenv('DB_NAME') ?: 'inscriptions';

    $conn = @new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["status" => "error", "db" => "unreachable"]);
    } else {
        http_response_code(200);
        echo json_encode(["status" => "ok", "db" => "connected"]);
    }
    exit;
}
// --- FIN ROUTE HEALTH ---

$host = getenv('DB_HOST') ?: 'inscription-db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASSWORD') ?: 'root';
$db   = getenv('DB_NAME') ?: 'inscriptions';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($nom) || empty($email)) {
        echo json_encode(["success" => false, "message" => "Veuillez remplir tous les champs."]);
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO users (nom, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $email);
        $stmt->execute();
        echo json_encode(["success" => true, "message" => "Inscription réussie ✅"]);
    } catch (mysqli_sql_exception $e) {
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            echo json_encode(["success" => false, "message" => "Cette adresse e‑mail est déjà inscrite ❌"]);
        } else {
            echo json_encode(["success" => false, "message" => "Une erreur est survenue lors de l'inscription ❌"]);
        }
    }
} else {
    echo json_encode(["success" => false, "message" => "Méthode non supportée."]);
}
?>