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

$user_id = $data['user_id'] ?? null;
$description = trim($data['description'] ?? '');

if (!$user_id || !$description) {
    echo json_encode(["status" => "error", "message" => "user_id and description are required"]);
    exit;
}

try {
    // التحقق من وجود المستخدم
    $checkUser = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $checkUser->execute([$user_id]);

    if (!$checkUser->fetch()) {
        echo json_encode(["status" => "error", "message" => "User not found"]);
        exit;
    }

    // إضافة المهمة
    $stmt = $pdo->prepare("INSERT INTO tasks (user_id, description) VALUES (?, ?)");
    $stmt->execute([$user_id, $description]);

    echo json_encode(["status" => "success", "message" => "Task added"]);
} catch (PDOException $e) {
    error_log("Insert error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Failed to add task"]);
}
?>

