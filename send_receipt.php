<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$items = $_POST['items'] ?? [];
$total = $_POST['total'] ?? '0.00';
$voucher = $_POST['voucher'] ?? '';
$discount = $_POST['discount'] ?? '0.00';

// Build receipt content
$subject = "Your Receipt from Ramarama.co";
$headers = "From: no-reply@myshop.com\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = "<h2>Thank you for your order, $name!</h2>";
$message .= "<p>Here is your receipt:</p>";
$message .= "<table border='1' cellpadding='5'><tr><th>Product</th><th>Qty</th><th>Subtotal</th></tr>";

foreach ($items as $item) {
    $product = htmlspecialchars($item['name']);
    $qty = (int)$item['quantity'];
    $subtotal = number_format($item['subtotal'], 2);
    $message .= "<tr><td>$product</td><td>$qty</td><td>RM$subtotal</td></tr>";
}

$message .= "</table>";

if (!empty($voucher)) {
    $message .= "<p><strong>Voucher:</strong> $voucher</p>";
    $message .= "<p><strong>Discount:</strong> RM" . number_format($discount, 2) . "</p>";
}

$message .= "<p><strong>Total Paid:</strong> RM" . number_format($total, 2) . "</p>";
$message .= "<p>We appreciate your business!</p>";

// Send the email
if (mail($email, $subject, $message, $headers)) {
    echo "Receipt sent successfully.";
} else {
    echo "Failed to send receipt.";
}
