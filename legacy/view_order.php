<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Order not found.";
    exit;
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Fetch order
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id AND user_id = :user_id");
$stmt->execute(['id' => $order_id, 'user_id' => $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found or access denied.";
    exit;
}

// Fetch order items with product images
$stmt_items = $pdo->prepare("
    SELECT oi.*, p.name, p.images 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = :order_id
");
$stmt_items->execute(['order_id' => $order_id]);
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

// Size mapping array
$sizeMap = [
    'small' => 'S',
    'medium' => 'M',
    'large' => 'L',
    'xl' => 'XL',
    '2xl' => 'XXL'
];

// Calculate discount amount if voucher applied
$discount_amount = 0;
$voucher = null;
if (!empty($order['voucher_code'])) {
    $stmt_voucher = $pdo->prepare("SELECT * FROM vouchers WHERE code = :code");
    $stmt_voucher->execute(['code' => $order['voucher_code']]);
    $voucher = $stmt_voucher->fetch(PDO::FETCH_ASSOC);

    if ($voucher) {
        if ($voucher['discount_type'] == 'flat') {
            $discount_amount = min($voucher['discount_value'], $order['subtotal']);
        } elseif ($voucher['discount_type'] == 'percentage') {
            $discount_amount = $order['subtotal'] * ($voucher['discount_value'] / 100);
            // If max_discount exists and > 0, cap discount amount
            if ($voucher['max_discount'] > 0 && $discount_amount > $voucher['max_discount']) {
                $discount_amount = $voucher['max_discount'];
            }
        }
        // Round to 2 decimals
        $discount_amount = round($discount_amount, 2);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?= htmlspecialchars($order_id) ?></title>
    <link rel="stylesheet" href="include/styles.css">
    <style>
        .product-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>
<div class="product-details-container">
    <h2>Order Details - #<?= htmlspecialchars($order_id) ?></h2>

    <table class="manage-products-table" border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Image</th>
                <th>Product</th>
                <th>Size</th>
                <th>Quantity</th>
                <th>Price (RM)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item): 
                $sizeLabel = $sizeMap[strtolower($item['size'])] ?? strtoupper($item['size']);
            ?>
                <tr>
                    <td>
                        <?php
                        $images = json_decode($item['images'], true);
                        if (!empty($images) && isset($images[0])): ?>
                            <img src="<?= htmlspecialchars($images[0]) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="product-image">
                        <?php else: ?>
                            <span>No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($item['name']) ?></td>
                    <td><?= htmlspecialchars($sizeLabel) ?></td>
                    <td><?= (int)$item['quantity'] ?></td>
                    <td><?= number_format($item['price'], 2) ?></td>
                    <td>
                        <a href="review.php?order_id=<?= urlencode($order_id) ?>&product_id=<?= urlencode($item['product_id']) ?>">
                            Leave a Review
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="price-info">
        <p><strong>Subtotal:</strong> RM<?= number_format($order['subtotal'], 2) ?></p>
        <?php if ($voucher): ?>
            <p class="discount"><strong>Discount(<?= htmlspecialchars($voucher['code']) ?>):</strong> -RM<?= number_format($discount_amount, 2) ?></p>
        <?php endif; ?>
        <p><strong>Total:</strong> RM<?= number_format($order['total'], 2) ?></p>
        <p><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></p>
    </div>

    <?php if (!empty($order['tracking_number'])): ?>
        <p><strong>Tracking Number:</strong> <?= htmlspecialchars($order['tracking_number']) ?></p>
        <div id="embedTrack"></div>
        <script src="//www.tracking.my/track-button.js"></script>
        <script>
            TrackButton.embed({
                selector: "#embedTrack",
                tracking_number: "<?= htmlspecialchars($order['tracking_number']) ?>"
            });
        </script>
    <?php else: ?>
        <p><em>Tracking number not available yet.</em></p>
    <?php endif; ?>
</div>
<?php include 'footer.php'; ?>
</body>
</html>
