<?php
require_once '../config/database.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$username = trim($_POST['username'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$confirm = $_POST['confirm_password'] ?? '';

if (!$username || !$email || !$password || !$confirm) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

if ($password !== $confirm) {
    echo json_encode(['success' => false, 'message' => 'Passwords do not match.']);
    exit;
}

$check = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ?");
$check->execute([$email, $username]);
if ($check->fetch()) {
    echo json_encode(['success' => false, 'message' => 'Email or username already exists.']);
    exit;
}

$hashed = password_hash($password, PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
$stmt->execute([$username, $email, $phone, $hashed]);

$_SESSION['user_id'] = $pdo->lastInsertId();
$_SESSION['username'] = $username;

echo json_encode(['success' => true, 'username' => $username]);
