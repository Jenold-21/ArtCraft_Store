<?php
require_once '../config/database.php';
require_once '../includes/functions.php';
header('Content-Type: application/json');

$category_id = isset($_GET['category']) ? (int)$_GET['category'] : null;
$featured = isset($_GET['featured']);
$products = getProducts($pdo, $category_id, $featured);
echo json_encode($products);
