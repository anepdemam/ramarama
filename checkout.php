<?php
session_start();
include('includes/db.php');

// Ensure the user is logged in as a customer
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: auth/login.php');
    exit;
}

// Initialize CSRF token if not set
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Safely initialize cart as an array to avoid undefined errors
$cart = isset($_SESSION['cart']) && is_array($_SESSION['cart']) ? $_SESSION['cart'] : [];
$voucher_code = $_SESSION['voucher_code'] ?? '';
$discount = $_SESSION['voucher_discount'] ?? 0.0;
$total_price = 0;

// Fetch cart items from session
$cart_items = [];
$product_ids = array_keys($cart);
if (!empty($product_ids)) {
    $placeholders = implode(',', array_fill(0, count($product_ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($product_ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Map products by id for quick lookup
    $products_map = [];
    foreach ($products as $product) {
        $products_map[$product['id']] = $product;
    }

    // Build cart items array
    foreach ($cart as $product_id => $sizes) {
        foreach ($sizes as $size => $qty) {
            if (isset($products_map[$product_id])) {
                $product = $products_map[$product_id];
                $subtotal = $product['price'] * $qty;
                $cart_items[] = [
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'quantity' => $qty,
                    'size' => strtoupper($size),
                    'subtotal' => $subtotal,
                ];
                $total_price += $subtotal;
            }
        }
    }
}

// Apply discount from voucher code
$final_total = max(0, $total_price - $discount);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Your Cart - Checkout</title>
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body class="checkout-page">

    <?php include('includes/header.php'); ?>

    <div class="checkout-container">
        <main class="checkout-content">
            <section class="checkout-summary">
                <h3>Order Summary</h3>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Size</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name']) ?></td>
                                <td>RM<?= number_format($item['price'], 2) ?></td>
                                <td><?= $item['quantity'] ?></td>
                                <td><?= htmlspecialchars($item['size']) ?></td>
                                <td>RM<?= number_format($item['subtotal'], 2) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="4" align="right"><strong>Subtotal:</strong></td>
                            <td>RM<?= number_format($total_price, 2) ?></td>
                        </tr>
                        <?php if ($discount > 0): ?>
                            <tr>
                                <td colspan="4" align="right"><strong>Discount
                                        (<?= htmlspecialchars($voucher_code) ?>):</strong></td>
                                <td>- RM<?= number_format($discount, 2) ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr>
                            <td colspan="4" align="right"><strong>Total:</strong></td>
                            <td><strong>RM<?= number_format($final_total, 2) ?></strong></td>
                        </tr>
                    </tbody>
                </table>
            </section>

            <section class="checkout-form">
                <h3>Shipping Details</h3>
                <form action="process_order.php" method="POST" class="checkout-form-fields">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">
                    <input type="hidden" name="voucher_code" value="<?= htmlspecialchars($voucher_code) ?>">
                    <input type="hidden" name="discount" value="<?= $discount ?>">

                    <?php foreach ($cart as $product_id => $sizes): ?>
                        <?php foreach ($sizes as $size => $quantity): ?>
                            <input type="hidden" name="quantity[<?= (int) $product_id ?>][<?= htmlspecialchars($size) ?>]"
                                value="<?= (int) $quantity ?>">
                            <input type="hidden" name="size[<?= (int) $product_id ?>][<?= htmlspecialchars($size) ?>]"
                                value="<?= htmlspecialchars($size) ?>">
                        <?php endforeach; ?>
                    <?php endforeach; ?>

                    <div class="form-group">
                        <input type="text" id="username" name="username" placeholder="Full Name" required
                            value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <input type="email" id="email" name="email" placeholder="Email Address" required
                            value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <input type="tel" id="phone" name="phone" placeholder="Phone Number" pattern="[0-9]{10,15}"
                            required>
                    </div>

                    <div class="form-group">
                        <textarea id="address" name="address" placeholder="Shipping Address" required></textarea>
                    </div>

                    <button type="submit" class="checkout-button">Place Order</button>
                </form>
            </section>
        </main>
    </div>

    <?php include('includes/footer.php'); ?>

</body>

</html>