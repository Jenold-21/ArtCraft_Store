<?php include 'header.php'; ?>

<div class="admin-content">
    <h1>Dashboard</h1>

    <?php
    $totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalCategories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
    $totalContacts = $pdo->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
    $totalOrders = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
    $totalRevenue = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status != 'cancelled'")->fetchColumn();
    $pendingOrders = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'pending'")->fetchColumn();
    ?>

    <div class="stats-grid">
        <div class="stat-card">
            <i class="fas fa-box"></i>
            <h3><?= $totalProducts ?></h3>
            <p>Total Products</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-tags"></i>
            <h3><?= $totalCategories ?></h3>
            <p>Categories</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-users"></i>
            <h3><?= $totalUsers ?></h3>
            <p>Users</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-truck"></i>
            <h3><?= $totalOrders ?></h3>
            <p>Total Orders</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-clock"></i>
            <h3><?= $pendingOrders ?></h3>
            <p>Pending Orders</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-dollar-sign"></i>
            <h3>₹<?= number_format($totalRevenue, 2) ?></h3>
            <p>Total Revenue</p>
        </div>
        <div class="stat-card">
            <i class="fas fa-envelope"></i>
            <h3><?= $totalContacts ?></h3>
            <p>Messages</p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
