<?php
require_once '../config/database.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login first.']);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT c.quantity, p.id, p.name, p.price
    FROM cart c JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
$stmt->execute([$user_id]);
$cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($cartItems)) {
    echo json_encode(['success' => false, 'message' => 'Your cart is empty.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$location = trim($_POST['location'] ?? '');

if (!$name || !$phone || !$location) {
    echo json_encode(['success' => false, 'message' => 'Please fill in all required fields.']);
    exit;
}

$total = 0;
foreach ($cartItems as $item) {
    $total += $item['price'] * $item['quantity'];
}

$pdo->beginTransaction();

try {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, name, phone, location, total, status) VALUES (?, ?, ?, ?, ?, 'pending')");
    $stmt->execute([$user_id, $name, $phone, $location, $total]);
    $orderId = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
    foreach ($cartItems as $item) {
        $stmt->execute([$orderId, $item['id'], $item['name'], $item['price'], $item['quantity']]);
    }

    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")->execute([$user_id]);
    $pdo->commit();

    echo json_encode(['success' => true, 'order_id' => $orderId, 'message' => 'Order placed successfully!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'message' => 'Failed to place order. Please try again.']);
}
