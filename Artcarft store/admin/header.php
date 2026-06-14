<?php
require_once '../config/database.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Artcraft Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<div class="admin-layout">
    <aside class="sidebar">
        <h2><a href="index.php">Artcraft Admin</a></h2>
        <nav>
            <a href="index.php" class="<?= $current_page == 'index.php' ? 'active' : '' ?>"><i class="fas fa-dashboard"></i> Dashboard</a>
            <a href="products.php" class="<?= $current_page == 'products.php' ? 'active' : '' ?>"><i class="fas fa-box"></i> Products</a>
            <a href="categories.php" class="<?= $current_page == 'categories.php' ? 'active' : '' ?>"><i class="fas fa-tags"></i> Categories</a>
            <a href="orders.php" class="<?= $current_page == 'orders.php' ? 'active' : '' ?>"><i class="fas fa-truck"></i> Orders</a>
            <a href="contacts.php" class="<?= $current_page == 'contacts.php' ? 'active' : '' ?>"><i class="fas fa-envelope"></i> Contacts</a>
            <a href="logout.php"><i class="fas fa-sign-out"></i> Logout</a>
        </nav>
    </aside>
    <main class="admin-main">
