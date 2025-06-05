<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
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

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$phone_number = trim($data['phone_number'] ?? '');
$password_raw = trim($data['password'] ?? '');

if (empty($username) || empty($email) || empty($password_raw)) {
    echo json_encode(["status" => "error", "message" => "Username, email and password are required"]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["status" => "error", "message" => "Invalid email format"]);
    exit;
}

// التحقق من تكرار البريد الإلكتروني أو اسم المستخدم أو رقم الهاتف
$check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? OR phone_number = ?");
$check->execute([$email, $username, $phone_number]);
if ($check->fetch()) {
    echo json_encode(["status" => "error", "message" => "Username, email or phone already registered"]);
    exit;
}

// تشفير كلمة المرور
$password_hashed = password_hash($password_raw, PASSWORD_DEFAULT);

// إدخال المستخدم
$insert = $pdo->prepare("INSERT INTO users (username, email, phone_number, password_hash) VALUES (?, ?, ?, ?)");
try {
    $insert->execute([$username, $email, $phone_number ?: null, $password_hashed]);
    echo json_encode(["status" => "success", "message" => "User registered"]);
} catch (PDOException $e) {
    error_log("Insert Error: " . $e->getMessage());
    echo json_encode(["status" => "error", "message" => "Registration failed. Please try again later."]);
}
?>
