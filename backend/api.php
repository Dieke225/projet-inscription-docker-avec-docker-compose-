<?php
header("Access-Control-Allow-Origin: http://localhost:8085");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$host = getenv('DB_HOST') ?: 'inscription-db';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: 'example';
$db   = getenv('DB_NAME') ?: 'inscriptions';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    http_response_code(500);
    echo "Erreur de connexion à la base de données.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');

    // Vérification des champs manquants
    $champsManquants = [];
    if (empty($nom)) $champsManquants[] = "le nom";
    if (empty($email)) $champsManquants[] = "l’adresse e‑mail";

    if (!empty($champsManquants)) {
        echo "Veuillez remplir " . implode(" et ", $champsManquants) . ".";
        exit;
    }

    try {
        $stmt = $conn->prepare("INSERT INTO users (nom, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $nom, $email);
        $stmt->execute();
        echo "Inscription réussie ✅";
    } catch (mysqli_sql_exception $e) {
        if (str_contains($e->getMessage(), 'Duplicate entry')) {
            echo "Cette adresse e‑mail est déjà inscrite ❌";
        } else {
            echo "Une erreur est survenue lors de l'inscription ❌";
        }
    }
} else {
    echo "Méthode non supportée.";
}
?>
