<?php
require_once '../config/database.php';
header('Content-Type: application/json');

$response = ['loggedIn' => false, 'cartCount' => 0];
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT COALESCE(SUM(quantity), 0) FROM cart WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $response['loggedIn'] = true;
    $response['user_id'] = $_SESSION['user_id'];
    $response['username'] = $_SESSION['username'];
    $response['cartCount'] = (int)$stmt->fetchColumn();
}
echo json_encode($response);
