<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Pagination setup
$limit = 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Size mapping array
$sizeMap = [
    'small' => 'S',
    'medium' => 'M',
    'large' => 'L',
    'xl' => 'XL',
    '2xl' => 'XXL'
];

// Get total number of successful orders
$total_stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE user_id = :user_id AND status = 'Successful'");
$total_stmt->execute(['user_id' => $user_id]);
$total_orders = $total_stmt->fetchColumn();
$total_pages = ceil($total_orders / $limit);

// Fetch successful orders with pagination
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = :user_id AND status = 'Successful' ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="include/styles.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="product-details-container">
    <h3>Order History</h3>
    <?php if (empty($orders)): ?>
        <p>You have no orders yet.</p>
    <?php else: ?>
        <table class="manage-products-table">
            <thead>
                <tr>
                    <th>Items</th>
                    <th>Total (RM)</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>
                            <ul style="list-style: none; padding-left: 0; margin: 0;">
                                <?php
                                $stmt_items = $pdo->prepare("
                                    SELECT p.name, oi.quantity, oi.size 
                                    FROM order_items oi 
                                    JOIN products p ON oi.product_id = p.id 
                                    WHERE oi.order_id = ?
                                ");
                                $stmt_items->execute([$order['id']]);
                                $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

                                foreach ($items as $item):
                                    $sizeLabel = $sizeMap[strtolower($item['size'])] ?? strtoupper($item['size']);
                                ?>
                                    <li><?= htmlspecialchars($item['name']) ?> (<?= $sizeLabel ?>) × <?= $item['quantity'] ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </td>
                        <td><?= number_format($order['total'], 2); ?></td>
                        <td><?= htmlspecialchars($order['status']); ?></td>
                        <td><a href="view_order.php?id=<?= $order['id']; ?>" class="link">View</a></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1; ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?= $i; ?>" class="<?= ($i === $page) ? 'active' : ''; ?>"><?= $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?= $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
