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

if (!$user_id || !is_numeric($user_id)) {
    echo json_encode(["status" => "error", "message" => "Valid user_id is required"]);
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

    // جلب المهام للمستخدم
    $stmt = $pdo->prepare("SELECT id, description, is_completed, created_at FROM tasks WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "status" => "success",
        "tasks" => $tasks
    ]);
} catch (PDOException $e) {
    error_log("Fetch tasks error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Failed to fetch tasks"]);
}
?>

