<?php
session_start();
include 'db.php'; // your DB connection file

// Get order ID or reference number from GET (adjust param name as needed)
$order_id = $_GET['order_id'] ?? $_GET['refno'] ?? null;

if (!$order_id) {
    die('Order reference missing.');
}

// Fetch order status from database
$stmt = $pdo->prepare("SELECT status FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    die('Order not found.');
}

if ($order['status'] === 'Successful') {
    unset($_SESSION['cart'], $_SESSION['voucher_code'], $_SESSION['voucher_discount']);
    $message = "Thank you! Your payment was successful.";
    $processing_msg = "Your order is now being processed.";
} else {
    $message = "Payment failed or was cancelled. Please try again.";
    $processing_msg = "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Payment Status</title>
<style>
        body {
            font-family: Arial, sans-serif;
            background-color: #000;
            color: #fff;
            text-align: center;
            padding: 60px 20px;
        }
        h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            font-weight: 700;
        }
        p {
            font-size: 1.2rem;
            margin-bottom: 40px;
            color: #ccc;
        }
        a {
            text-decoration: none;
            background-color: #fff;
            color: #000;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: 700;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        a:hover {
            background-color: #333;
            color: #fff;
        }
    </style>
</head>
<body>
    <h2><?= htmlspecialchars($message) ?></h2>
    <?php if ($processing_msg): ?>
        <p><?= htmlspecialchars($processing_msg) ?></p>
    <?php endif; ?>
    <a href="products.php">Back to Merchant</a>
</body>
</html>
