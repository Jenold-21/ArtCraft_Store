<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdminLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function getCartCount($pdo, $user_id) {
    $stmt = $pdo->prepare("SELECT SUM(quantity) FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn() ?: 0;
}

function getCategories($pdo) {
    $stmt = $pdo->query("SELECT * FROM categories ORDER BY name");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProducts($pdo, $category_id = null, $featured = false) {
    $sql = "SELECT p.*, c.name AS category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id 
            WHERE 1=1";
    $params = [];

    if ($featured) {
        $sql .= " AND p.featured = 1";
    }
    if ($category_id) {
        $sql .= " AND p.category_id = ?";
        $params[] = $category_id;
    }
    $sql .= " ORDER BY p.created_at DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getProductById($pdo, $id) {
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name 
                           FROM products p 
                           LEFT JOIN categories c ON p.category_id = c.id 
                           WHERE p.id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
?>
