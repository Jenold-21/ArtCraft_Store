<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo json_encode(['success' => false, 'loggedIn' => false, 'message' => 'Not logged in.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Please login first.', 'redirect' => 'login.html']);
    }
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("
        SELECT c.id AS cart_id, c.quantity, p.id, p.name, p.price, p.image
        FROM cart c JOIN products p ON c.product_id = p.id
        WHERE c.user_id = ?
    ");
    $stmt->execute([$user_id]);
    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total = 0;
    foreach ($items as $item) $total += $item['price'] * $item['quantity'];

    echo json_encode(['success' => true, 'items' => $items, 'total' => $total]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = (int)($_POST['product_id'] ?? 0);
    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Invalid product.']);
        exit;
    }

    $check = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
    $check->execute([$user_id, $product_id]);
    $existing = $check->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        $stmt = $pdo->prepare("UPDATE cart SET quantity = quantity + 1 WHERE id = ?");
        $stmt->execute([$existing['id']]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
        $stmt->execute([$user_id, $product_id]);
    }

    echo json_encode(['success' => true, 'message' => 'Product added to cart!']);
    exit;
}
