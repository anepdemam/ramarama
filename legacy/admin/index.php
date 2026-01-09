<?php
session_start();
require '../includes/db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Handle form submission to update tracking number
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['tracking_number'])) {
    $stmtUpdate = $pdo->prepare("UPDATE orders SET tracking_number = :tracking_number WHERE id = :order_id");
    $stmtUpdate->execute([
        'tracking_number' => $_POST['tracking_number'],
        'order_id' => $_POST['order_id']
    ]);
    // Redirect to avoid resubmission and refresh data
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$size_mapping = [
    'small' => 'S',
    'medium' => 'M',
    'large' => 'L',
    'xl' => 'XL',
    '2xl' => 'XXL'
];

try {
    // Fetch stats
    $productCount = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $customerCount = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'customer'")->fetchColumn();
    $orderCount = $pdo->query("SELECT COUNT(*) FROM orders WHERE status = 'Successful'")->fetchColumn();
    $totalEarnings = $pdo->query("SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'Successful'")->fetchColumn();

    // Fetch successful orders joined with users, order items, and products
    $stmt = $pdo->query("
        SELECT o.*, u.username, oi.product_id, oi.quantity, oi.size, oi.price AS item_price, p.name AS product_name
        FROM orders o
        JOIN users u ON o.user_id = u.id
        LEFT JOIN order_items oi ON o.id = oi.order_id
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE o.status = 'Successful'
        ORDER BY o.created_at DESC
    ");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Group orders by order ID
    $groupedOrders = [];
    foreach ($rows as $row) {
        $orderId = $row['id'];
        if (!isset($groupedOrders[$orderId])) {
            $groupedOrders[$orderId] = $row;
            $groupedOrders[$orderId]['items'] = [];
        }
        if ($row['product_id']) {
            $groupedOrders[$orderId]['items'][] = [
                'product_name' => $row['product_name'],
                'quantity' => $row['quantity'],
                'size' => $row['size'],
                'item_price' => $row['item_price']
            ];
        }
    }

} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Dashboard - Successful Orders</title>
    <link rel="stylesheet" href="../assets/css/main.css" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* Table styling similar to order_logs */
        .manage-products-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .manage-products-table th,
        .manage-products-table td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }

        .tracking-input {
            width: 140px;
            padding: 5px;
        }

        .btn-submit {
            padding: 6px 10px;
            margin-top: 5px;
            cursor: pointer;
            border: none;
            background-color: #4CAF50;
            color: white;
            border-radius: 3px;
        }

        .btn-back {
            margin-top: 20px;
            display: inline-block;
            padding: 8px 15px;
            background-color: #555;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>

<body class="admin-page manage-products">

    <?php include '../includes/sidebar.php'; ?>

    <main class="unique-admin-main">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
        </div>

        <div class="dashboard-summary">
            <div class="summary-box">
                <i class="fas fa-box"></i>
                <div class="details">
                    <h3><?= htmlspecialchars($productCount) ?></h3>
                    <span>Products</span>
                </div>
            </div>
            <div class="summary-box">
                <i class="fas fa-users"></i>
                <div class="details">
                    <h3><?= htmlspecialchars($customerCount) ?></h3>
                    <span>Customers</span>
                </div>
            </div>
            <div class="summary-box">
                <i class="fas fa-shopping-cart"></i>
                <div class="details">
                    <h3><?= htmlspecialchars($orderCount) ?></h3>
                    <span>Successful Orders</span>
                </div>
            </div>
            <div class="summary-box">
                <i class="fas fa-dollar-sign"></i>
                <div class="details">
                    <h3>RM<?= number_format($totalEarnings, 2) ?></h3>
                    <span>Earnings</span>
                </div>
            </div>
        </div>

        <h2>Successful Orders</h2>

        <?php if (empty($groupedOrders)): ?>
            <p>No successful orders found.</p>
        <?php else: ?>
            <table class="manage-products-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Items Ordered</th>
                        <th>Shipping Details</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Tracking Number</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($groupedOrders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td>
                                <ul>
                                    <?php foreach ($order['items'] as $item): ?>
                                        <li>
                                            <?= htmlspecialchars($item['product_name']) ?>
                                            (<?= htmlspecialchars($size_mapping[strtolower($item['size'])] ?? $item['size']) ?>)
                                            x <?= htmlspecialchars($item['quantity']) ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td>
                                <p>Name: <?= htmlspecialchars($order['shipping_name']) ?></p>
                                <p>Phone: <?= htmlspecialchars($order['shipping_phone']) ?></p>
                                <p>Address: <?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                            </td>
                            <td><?= htmlspecialchars($order['created_at']) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td>
                                <?= htmlspecialchars($order['tracking_number'] ?: '-') ?>
                                <form method="POST" class="tracking-form">
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                                    <input type="text" name="tracking_number"
                                        value="<?= htmlspecialchars($order['tracking_number']) ?>" placeholder="Enter tracking"
                                        class="tracking-input">
                                    <button type="submit" class="btn-submit">Save</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <p><a href="index.php" class="btn-back">Back to Admin Dashboard</a></p>
    </main>

</body>

</html>