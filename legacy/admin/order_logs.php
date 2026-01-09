<?php
session_start();
require 'db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle form submission to update tracking number
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['tracking_number'])) {
    $stmt = $pdo->prepare("UPDATE orders SET tracking_number = :tracking_number WHERE id = :order_id");
    $stmt->execute([
        'tracking_number' => $_POST['tracking_number'],
        'order_id' => $_POST['order_id']
    ]);
}

// Get all orders with associated user information and order items, including product name
$stmt = $pdo->query("
    SELECT o.*, u.username, oi.product_id, oi.quantity, oi.size, oi.price AS item_price, p.name AS product_name
    FROM orders o
    JOIN users u ON o.user_id = u.id
    LEFT JOIN order_items oi ON o.id = oi.order_id
    LEFT JOIN products p ON oi.product_id = p.id
    ORDER BY o.created_at DESC
");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$size_mapping = [
    'small' => 'S',
    'medium' => 'M',
    'large' => 'L',
    'xl' => 'XL',
    '2xl' => 'XXL'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Logs</title>
    <link rel="stylesheet" href="include/styles.css">  <!-- Linking to the same stylesheet -->
</head>
<body class="admin-page manage-products">

<?php include 'sidebar.php'; // Include sidebar for consistency ?>

<div class="manage-products-container">
    <h2>All Order Logs</h2>

    <?php if (!$orders): ?>
        <p>No orders found.</p>
    <?php else: ?>
        <table class="manage-products-table" border="1">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Items Ordered</th>
                    <th>Shipping Details</th>
                    <th>Date</th>
                    <th>Status</th>

                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through the orders
                foreach ($orders as $order):
                    // Get items for this specific order
                    $items = [];
                    foreach ($orders as $item) {
                        if ($item['id'] == $order['id']) {
                            $items[] = $item;
                        }
                    }
                ?>
                    <tr>
                        <td><?= htmlspecialchars($order['id']) ?></td>
                        <td>
                            <ul>
                                <?php foreach ($items as $item): ?>
                                    <li>
                                        <?= htmlspecialchars($item['product_name']) ?> 
                                        (<?= htmlspecialchars($size_mapping[strtolower($item['size'])] ?? $item['size']); ?>) 
                                        x <?= htmlspecialchars($item['quantity']); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td>
                            <p>Name: <?= htmlspecialchars($order['shipping_name']) ?></p>
                            <p>Phone: <?= htmlspecialchars($order['shipping_phone']) ?></p>
                            <p>Address: <?= htmlspecialchars($order['shipping_address']) ?></p>
                        </td>
                        <td><?= htmlspecialchars($order['created_at']) ?></td>
                        <td><?= htmlspecialchars($order['status']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="admin_dashboard.php" class="btn-back">Back to Admin Dashboard</a></p>
</div>

</body>
</html>
