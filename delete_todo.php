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

$task_id = $data['id'] ?? null;

if (!$task_id || !is_numeric($task_id)) {
    echo json_encode(["status" => "error", "message" => "Valid task ID is required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$task_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Task deleted"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Task not found or already deleted"]);
    }
} catch (PDOException $e) {
    error_log("Delete error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Failed to delete task"]);
}
?>
