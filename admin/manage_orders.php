<?php
session_start();
require '../includes/db.php';

// Check if the user is an admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// CSRF Token generation
function generateCSRFToken()
{
    return bin2hex(random_bytes(32));  // Generate a secure random token
}

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCSRFToken();
}

$csrf_token = $_SESSION['csrf_token'];  // Store the CSRF token for the form

// Handle tracking number update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['tracking_number'], $_POST['csrf_token'])) {
    // CSRF Token validation
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    // Get form data
    $orderId = $_POST['order_id'];
    $trackingNumber = $_POST['tracking_number'];

    // Update tracking number in the database
    try {
        $stmt = $pdo->prepare("UPDATE orders SET tracking_number = :tracking_number WHERE id = :order_id");
        $stmt->execute([
            'tracking_number' => $trackingNumber,
            'order_id' => $orderId
        ]);
        $success = "Tracking number updated successfully!";
    } catch (PDOException $e) {
        $error = "Error updating tracking number: " . $e->getMessage();
    }
}

// Fetch orders with user information
$stmt = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY created_at DESC");
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Orders</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
    <div class="product-details-container">
        <h3>Order Logs (Admin)</h3>

        <!-- Success or Error Message -->
        <?php if (isset($success))
            echo "<p class='success-msg'>$success</p>"; ?>
        <?php if (isset($error))
            echo "<p class='error-msg'>$error</p>"; ?>

        <table class="manage-products-table" border="1">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tracking Number</th>
                    <th>Update Tracking</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['username']); ?></td>
                        <td>RM<?= number_format($order['total'], 2); ?></td>
                        <td><?= htmlspecialchars($order['status']); ?></td>
                        <td><?= htmlspecialchars($order['tracking_number']); ?></td>
                        <td>
                            <form method="post" action="manage_orders.php">
                                <input type="hidden" name="order_id" value="<?= $order['id']; ?>">
                                <input type="text" name="tracking_number" placeholder="Enter tracking number"
                                    value="<?= htmlspecialchars($order['tracking_number']); ?>" required>
                                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>"> <!-- CSRF Token -->
                                <button type="submit">Save</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>