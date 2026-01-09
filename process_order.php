<?php
session_start();
include 'db.php';

$config = require 'C:/xampp/htdocs/config.php';

// Validate user session
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: login.php');
    exit;
}

// Validate request method and CSRF token
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || 
    !isset($_POST['csrf_token'], $_SESSION['csrf_token']) || 
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    die('Invalid request');
}

// Validate shipping info
$errors = [];
$shipping_name  = trim($_POST['username'] ?? '');
$shipping_email = trim($_POST['email'] ?? '');
$shipping_phone = trim($_POST['phone'] ?? '');
$shipping_addr  = trim($_POST['address'] ?? '');

if (empty($shipping_name)) $errors[] = 'Name is required';
if (!filter_var($shipping_email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email';
if (!preg_match('/^[0-9]{10,15}$/', $shipping_phone)) $errors[] = 'Invalid phone number';
if (empty($shipping_addr)) $errors[] = 'Address is required';

if ($errors) {
    $_SESSION['checkout_errors'] = $errors;
    header('Location: checkout.php');
    exit;
}

// Validate cart data
$allowed_sizes = ['small', 'medium', 'large', 'xl', '2xl'];
$cart = [];

if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
    foreach ($_POST['quantity'] as $product_id => $sizes) {
        $product_id = (int)$product_id;
        if ($product_id <= 0) continue;
        
        foreach ($sizes as $size => $qty) {
            $clean_size = strtolower(trim($size));
            if (!in_array($clean_size, $allowed_sizes)) continue;
            
            $qty = (int)$qty;
            if ($qty <= 0) continue;
            
            $cart[$product_id][$clean_size] = $qty;
        }
    }
}

if (empty($cart)) {
    die("No valid cart items submitted");
}

// Process voucher if any
$voucher_code = $_POST['voucher_code'] ?? '';
$discount = (float)($_POST['discount'] ?? 0.0);

if ($voucher_code) {
    $stmt = $pdo->prepare("SELECT * FROM vouchers WHERE code = :code AND (expiration_date IS NULL OR expiration_date >= CURDATE())");
    $stmt->execute(['code' => $voucher_code]);
    $voucher = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$voucher) {
        $voucher_code = '';
        $discount = 0.0;
    }
}

// Fetch products and validate stock (optional here, can also be deferred)
$product_ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($product_ids), '?'));
$stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$products_map = [];
foreach ($products as $product) {
    $products_map[$product['id']] = $product;
}

$cart_items = [];
$total_price = 0.0;

foreach ($cart as $product_id => $sizes) {
    if (!isset($products_map[$product_id])) {
        die("Product ID $product_id not found");
    }
    $product = $products_map[$product_id];

    foreach ($sizes as $size => $qty) {
        $subtotal = $product['price'] * $qty;
        $cart_items[] = [
            'product_id' => $product_id,
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $qty,
            'subtotal' => $subtotal,
            'size' => $size,
        ];
        $total_price += $subtotal;
    }
}

$final_total = max(0, $total_price - $discount);

// Process order
try {
    $pdo->beginTransaction();

    // Insert order
    $sql_order = "INSERT INTO orders 
        (user_id, voucher_code, subtotal, discount, total, shipping_name, shipping_email, shipping_phone, shipping_address, created_at, status)
        VALUES (:uid, :voucher, :subtotal, :discount, :total, :sname, :semail, :sphone, :saddr, NOW(), 'Pending')";
    $stmt_order = $pdo->prepare($sql_order);
    $stmt_order->execute([
        'uid' => $_SESSION['user_id'],
        'voucher' => $voucher_code ?: null,
        'subtotal' => $total_price,
        'discount' => $discount,
        'total' => $final_total,
        'sname' => $shipping_name,
        'semail' => $shipping_email,
        'sphone' => $shipping_phone,
        'saddr' => $shipping_addr,
    ]);
    $order_id = (int)$pdo->lastInsertId();

    // Insert order items WITHOUT updating stock
    $sql_item = "INSERT INTO order_items (order_id, product_id, price, quantity, subtotal, size) VALUES (:oid, :pid, :price, :qty, :subtotal, :size)";
    $stmt_item = $pdo->prepare($sql_item);

    foreach ($cart_items as $item) {
        $stmt_item->execute([
            'oid' => $order_id,
            'pid' => $item['product_id'],
            'price' => $item['price'],
            'qty' => $item['quantity'],
            'subtotal' => $item['subtotal'],
            'size' => $item['size'],
        ]);
    }

    $pdo->commit();

    // Prepare ToyyibPay bill data
    $data = [
        'userSecretKey' => $config['toyyibpay']['secret_key'],
        'categoryCode' => $config['toyyibpay']['category_code'],
        'billName' => 'Order #' . $order_id,
        'billDescription' => 'Payment for order #' . $order_id,
        'billPriceSetting' => 1,
        'billPayorInfo' => 1,
        'billAmount' => (int)round($final_total * 100),
        'billEmail' => $shipping_email,
        'billPhone' => $shipping_phone,
        'billTo' => $shipping_name,
        'billReturnUrl' => $config['toyyibpay']['return_url'],
        'billCallbackUrl' => $config['toyyibpay']['callback_url'],
        'billExternalReferenceNo' => $order_id,
        'billExpiryDate' => date('Y-m-d H:i:s', strtotime('+1 day')),
    ];

    // Call ToyyibPay API
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://dev.toyyibpay.com/index.php/api/createBill');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_POST, 1);
    $response = curl_exec($ch);
    curl_close($ch);

    $bill = json_decode($response, true);

    if (isset($bill[0]['BillCode'])) {
        $bill_code = $bill[0]['BillCode'];
        header('Location: https://dev.toyyibpay.com/' . $bill_code);
        exit;
    } else {
        throw new Exception('Payment gateway error: ' . $response);
    }

} catch (Exception $e) {
    $pdo->rollBack();
    error_log('Order processing error: ' . $e->getMessage());
    die('Order processing error. Please try again.');
}
