<?php
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if ($email && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ?");
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            header('Location: index.php');
            exit;
        } else {
            $error = '<div class="alert alert-error">Invalid credentials.</div>';
        }
    } else {
        $error = '<div class="alert alert-error">Please fill in all fields.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Artcraft Store</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body { display: flex; justify-content: center; align-items: center; min-height: 100vh; background: #2c3e50; }
        .form-container { width: 100%; max-width: 400px; margin: 20px; }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 style="text-align:center;color:#fff;margin-bottom:20px;">Admin Login</h2>
        <?= $error ?>
        <form method="POST" action="">
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="form-footer" style="color:#ccc;">
                <a href="../index.php">Back to Store</a>
            </div>
        </form>
    </div>
</body>
</html>
