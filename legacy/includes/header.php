<?php
require 'db.php';
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Ramarama Store</title>
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>
    <header class="navbar">
        <div class="container">
            <div class="logo">
                <img src="assets/img/logo.jpg" alt="Logo" class="logo-img" width="60" />

                <?php if (isset($_SESSION['user_id'])): ?>
                    <?php
                    // Fetch the username from the database
                    $stmt = $pdo->prepare("SELECT username FROM users WHERE id = :id");
                    $stmt->execute(['id' => $_SESSION['user_id']]);
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($user): ?>
                        <span class="greeting">Hi, <?= htmlspecialchars($user['username']); ?>!</span>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
            <nav>
                <ul class="menu">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="cart.php">Cart</a></li>
                    <li><a href="#contact">Contact Us</a></li>
                    <li><a href="order_history.php">History</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="auth/logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="auth/login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>