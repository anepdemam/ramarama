<?php
session_start();
include('includes/db.php');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: auth/login.php');
    exit;
}

// Initialize CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$size_map = [
    'small' => 'S',
    'medium' => 'M',
    'large' => 'L',
    'xl' => 'XL',
    '2xl' => 'XXL',
];

$allowed_sizes = ['small', 'medium', 'large', 'xl', '2xl'];

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Retrieve voucher info from session
$voucher_code = $_SESSION['voucher_code'] ?? '';
$discount = $_SESSION['voucher_discount'] ?? 0.0;

// ADD TO CART
if (isset($_GET['add_to_cart'])) {
    $product_id = (int) $_GET['add_to_cart'];
    $quantity = isset($_GET['quantity']) ? max(1, intval($_GET['quantity'])) : 1;
    $size = (isset($_GET['size']) && in_array($_GET['size'], $allowed_sizes)) ? $_GET['size'] : null;

    if (!$size) {
        $_SESSION['error'] = "Please select a valid size";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    // Check stock before adding
    $stmt = $pdo->prepare("SELECT stock_$size FROM products WHERE id = ?");
    $stmt->execute([$product_id]);
    $stock = $stmt->fetchColumn();

    if ($stock < $quantity) {
        $_SESSION['error'] = "Not enough stock available";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if (isset($_SESSION['cart'][$product_id][$size])) {
        $_SESSION['cart'][$product_id][$size] += $quantity;
    } else {
        $_SESSION['cart'][$product_id][$size] = $quantity;
    }

    header("Location: cart.php");
    exit;
}

// REMOVE FROM CART
if (isset($_GET['remove_product']) && isset($_GET['remove_size'])) {
    $product_id = (int) $_GET['remove_product'];
    $size = $_GET['remove_size'];

    if (isset($_SESSION['cart'][$product_id][$size])) {
        unset($_SESSION['cart'][$product_id][$size]);
        if (empty($_SESSION['cart'][$product_id])) {
            unset($_SESSION['cart'][$product_id]);
        }
    }

    header("Location: cart.php");
    exit;
}

// CLEAR CART
if (isset($_GET['clear_cart'])) {
    unset($_SESSION['cart'], $_SESSION['voucher_code'], $_SESSION['voucher_discount']);
    header("Location: cart.php");
    exit;
}

// Fetch products in cart
$cart_items = [];
$total_price = 0.0;

if (!empty($_SESSION['cart'])) {
    $product_ids = array_keys($_SESSION['cart']);
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map products by id for quick lookup
    $products_map = [];
    foreach ($products as $product) {
        $products_map[$product['id']] = $product;
    }

    // Build cart items with size and quantities
    foreach ($_SESSION['cart'] as $pid => $sizes) {
        foreach ($sizes as $size => $qty) {
            if (!isset($products_map[$pid]))
                continue;
            $product = $products_map[$pid];
            $subtotal = $product['price'] * $qty;

            $cart_items[] = [
                'id' => $pid,
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'quantity' => $qty,
                'subtotal' => $subtotal,
                'size' => $size,
            ];
            $total_price += $subtotal;
        }
    }
}

// Voucher code logic
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token.');
    }

    $voucher_code = isset($_POST['voucher_code']) ? trim($_POST['voucher_code']) : '';

    if (!empty($voucher_code)) {
        $stmt = $pdo->prepare("SELECT * FROM vouchers WHERE code = :code AND (expiration_date IS NULL OR expiration_date >= CURDATE())");
        $stmt->execute([':code' => $voucher_code]);
        $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($voucher) {
            if ($total_price >= $voucher['min_spend']) {
                if ($voucher['discount_type'] === 'percentage') {
                    $discount = $total_price * ($voucher['discount_value'] / 100);
                    if (!is_null($voucher['max_discount']) && $discount > $voucher['max_discount']) {
                        $discount = $voucher['max_discount'];
                    }
                } else {
                    $discount = min($voucher['discount_value'], $voucher['max_discount'] ?? $voucher['discount_value']);
                }

                $_SESSION['voucher_code'] = $voucher_code;
                $_SESSION['voucher_discount'] = $discount;

                $success = "Voucher applied! Discount: RM" . number_format($discount, 2);
            } else {
                $error = "Minimum spend of RM" . number_format($voucher['min_spend'], 2) . " required for this voucher!";
            }
        } else {
            $error = "Invalid or expired voucher code!";
            unset($_SESSION['voucher_code'], $_SESSION['voucher_discount']);
            $discount = 0.0;
        }
    } else {
        unset($_SESSION['voucher_code'], $_SESSION['voucher_discount']);
        $discount = 0.0;
    }
}

$total_price = max(0, $total_price - $discount);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Your Cart</title>
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>

    <div class="cart-container">
        <h2>Your Shopping Cart</h2>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error-msg"><?= htmlspecialchars($_SESSION['error']) ?></p>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($cart_items)): ?>
            <p>Your cart is empty!</p>
            <a href="products.php" class="back-btn">Back to Products</a>
        <?php else: ?>
            <table class="cart-table" border="1" cellspacing="0" cellpadding="6">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Description</th>
                        <th>Price (RM)</th>
                        <th>Quantity</th>
                        <th>Size</th>
                        <th>Subtotal (RM)</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['name']) ?></td>
                            <td><?= htmlspecialchars($item['description']) ?></td>
                            <td><?= number_format($item['price'], 2) ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= htmlspecialchars($size_map[$item['size']] ?? ucfirst($item['size'])) ?></td>
                            <td><?= number_format($item['subtotal'], 2) ?></td>
                            <td>
                                <a href="cart.php?remove_product=<?= $item['id'] ?>&remove_size=<?= $item['size'] ?>"
                                    class="cart-action-btn">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3>Total: RM<?= number_format($total_price, 2) ?></h3>

            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                <input type="text" name="voucher_code" placeholder="Enter voucher code"
                    value="<?= htmlspecialchars($voucher_code) ?>" />
                <button type="submit">Apply Voucher</button>
            </form>

            <?php if ($success): ?>
                <p class="success-msg"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <?php if ($error): ?>
                <p class="error-msg"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>

            <div class="cart-actions">
                <a href="products.php" class="back-btn">Continue Shopping</a>
                <a href="cart.php?clear_cart=1" class="cart-clear-btn">Clear Cart</a>
                <a href="checkout.php" class="checkout-btn">Checkout</a>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>