<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once 'db.php';

$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
    exit;
}

$email = trim($data['email'] ?? '');
$password_input = trim($data['password'] ?? '');

if (empty($email) || empty($password_input)) {
    echo json_encode(["status" => "error", "message" => "Email and password are required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password_input, $user['password_hash'])) {
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "user_id" => $user['id']
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => $user ? "Wrong password" : "User not found"
        ]);
    }
} catch (PDOException $e) {
    error_log("Login error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Login failed. Please try again later."]);
}
?>
