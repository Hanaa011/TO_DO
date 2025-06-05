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
$description = trim($data['description'] ?? '');
$is_completed = $data['is_completed'] ?? null;

if (!$task_id || empty($description) || !is_numeric($is_completed)) {
    echo json_encode(["status" => "error", "message" => "All fields (id, description, is_completed) are required"]);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE tasks SET description = ?, is_completed = ? WHERE id = ?");
    $stmt->execute([$description, $is_completed, $task_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(["status" => "success", "message" => "Task updated"]);
    } else {
        echo json_encode(["status" => "warning", "message" => "No changes made or task not found"]);
    }
} catch (PDOException $e) {
    error_log("Update error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Failed to update task"]);
}
?>
