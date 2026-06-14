<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artcraft Store</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header>
    <div class="container">
        <div class="header-inner">
            <a href="index.php" class="logo">Artcraft Store</a>
            <nav class="navbar">
                <a href="index.php">Home</a>
                <a href="products.php">Products</a>
                <a href="contact.php">Contact</a>
                <?php if (isLoggedIn()): ?>
                    <a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart (<?= getCartCount($pdo, $_SESSION['user_id']) ?>)</a>
                    <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="signup.php">Sign Up</a>
                <?php endif; ?>
            </nav>
            <div class="menu-toggle"><i class="fas fa-bars"></i></div>
        </div>
    </div>
</header>

<main>
