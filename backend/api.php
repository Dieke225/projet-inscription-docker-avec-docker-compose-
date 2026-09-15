<?php
// ✅ Nettoyage des headers CORS obsolètes (Nginx élimine le besoin de Cross-Origin)
header("Content-Type: application/json; charset=UTF-8");

ini_set('display_errors', 0);
error_reporting(0);

// --- FONCTION DE CONNEXION AVEC RECONNEXION AUTOMATIQUE (ANTI-ERREUR 500) ---
function get_db_connection() {
    $host = getenv('DB_HOST') ?: 'inscription-db';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: 'root';
    $db   = getenv('DB_NAME') ?: 'inscriptions';

    $max_attempts = 5;
    $attempts = 0;
    
    mysqli_report(MYSQLI_REPORT_OFF); // Désactive les warnings d'affichage brut de mysqli

    while ($attempts < $max_attempts) {
        $conn = @new mysqli($host, $user, $pass, $db);
        
        if (!$conn->connect_error) {
            return $conn; // Connexion réussie !
        }
        
        $attempts++;
        sleep(2); // Attend 2 secondes avant la prochaine tentative (laisse le temps à MySQL d'exécuter init.sql)
    }
    
    return false; // Échec après toutes les tentatives
}
// --- FIN DE LA FONCTION ---


// --- ROUTE HEALTH ---
if (strpos($_SERVER['REQUEST_URI'], '/health') !== false) {
    $conn = get_db_connection();

    if (!$conn) {
        http_response_code(500);
        echo json_encode(["status" => "error", "db" => "unreachable"]);
    } else {
        http_response_code(200);
        echo json_encode(["status" => "ok", "db" => "connected"]);
        $conn->close();
    }
    exit;
}
// --- FIN ROUTE HEALTH ---


// Connexion principale pour la route d'inscription
$conn = get_db_connection();

if (!$conn) {
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

$conn->close();
?>
