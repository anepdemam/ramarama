<?php
include 'db.php';

// Log all incoming request data for debugging
file_put_contents('payment_callback.log', date('Y-m-d H:i:s') . " CALLBACK RECEIVED: " . json_encode($_REQUEST) . PHP_EOL, FILE_APPEND);

// Get order_id and payment_status from GET or POST
$order_id = $_GET['order_id'] ?? $_POST['order_id'] ?? '';
$payment_status = $_GET['status'] ?? $_POST['status'] ?? '';

// Validate presence of parameters
if (empty($order_id) || empty($payment_status)) {
    file_put_contents('payment_callback.log', "Missing parameters: order_id or status.\n", FILE_APPEND);
    http_response_code(400);
    echo "Missing parameters";
    exit;
}

// Validate order_id is numeric to avoid injection
if (!is_numeric($order_id)) {
    file_put_contents('payment_callback.log', "Invalid order ID: $order_id\n", FILE_APPEND);
    http_response_code(400);
    echo "Invalid order ID";
    exit;
}

// Normalize payment status to lowercase and trim whitespace
$payment_status = strtolower(trim($payment_status));

// Map payment status to database status, including '3' as failure
if (in_array($payment_status, ['1', 'paid', 'success'], true)) {
    $new_status = 'Successful';
} elseif (in_array($payment_status, ['0', '3', 'failed', 'cancelled', 'canceled'], true)) {
    $new_status = 'Failed';
} else {
    file_put_contents('payment_callback.log', "Unknown payment status: $payment_status\n", FILE_APPEND);
    http_response_code(400);
    echo "Unknown payment status";
    exit;
}

// Prepare and execute update query
try {
    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
    $stmt->bindParam(':status', $new_status, PDO::PARAM_STR);
    $stmt->bindParam(':id', $order_id, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        file_put_contents('payment_callback.log', "Order updated successfully: id=$order_id, status=$new_status\n", FILE_APPEND);

        // Deduct stock only if payment was successful
        if ($new_status === 'Successful') {
            // Fetch ordered items for this order
            $stmtItems = $pdo->prepare("SELECT product_id, size, quantity FROM order_items WHERE order_id = ?");
            $stmtItems->execute([$order_id]);
            $order_items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            $allowed_sizes = ['small', 'medium', 'large', 'xl', '2xl'];

            foreach ($order_items as $item) {
                $product_id = $item['product_id'];
                $size = strtolower($item['size']);  // normalize size string
                $qty = (int)$item['quantity'];

                // Skip if size is invalid
                if (!in_array($size, $allowed_sizes)) continue;

                $stock_column = "stock_" . $size;

                // Deduct stock, ensuring it doesn't go negative
                $updateStock = $pdo->prepare("UPDATE products SET $stock_column = GREATEST($stock_column - ?, 0) WHERE id = ?");
                $updateStock->execute([$qty, $product_id]);
            }

            file_put_contents('payment_callback.log', "Stock updated for order id=$order_id\n", FILE_APPEND);
        }

        http_response_code(200);
        echo $new_status;
    } else {
        file_put_contents('payment_callback.log', "No order found with id: $order_id\n", FILE_APPEND);
        http_response_code(404);
        echo "Order not found";
    }
} catch (PDOException $e) {
    file_put_contents('payment_callback.log', "Database error: " . $e->getMessage() . "\n", FILE_APPEND);
    http_response_code(500);
    echo "Server error";
}
exit;
