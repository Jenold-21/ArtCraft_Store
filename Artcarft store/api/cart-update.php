<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
header('Content-Type: application/json');

if (!isLoggedIn() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false]);
    exit;
}

$cart_id = (int)($_POST['cart_id'] ?? 0);
$user_id = $_SESSION['user_id'];

if (isset($_POST['remove'])) {
    $stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->execute([$cart_id, $user_id]);
    echo json_encode(['success' => true, 'removed' => true]);
} elseif (isset($_POST['quantity'])) {
    $qty = max(1, (int)$_POST['quantity']);
    $stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$qty, $cart_id, $user_id]);
    echo json_encode(['success' => true, 'quantity' => $qty]);
} else {
    echo json_encode(['success' => false]);
}
