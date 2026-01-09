<?php
session_start();
include('db.php');

// Check admin access (redirect if not admin)
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $code = strtoupper(trim($_POST['code']));
    $discount_type = $_POST['discount_type'];
    $discount_value = (float)$_POST['discount_value'];
    $min_spend = (float)$_POST['min_spend'];
    $max_discount = isset($_POST['max_discount']) ? (float)$_POST['max_discount'] : null;
    $expiration_date = !empty($_POST['expiration_date']) ? $_POST['expiration_date'] : null;

    // Basic validation
    if (empty($code) || $discount_value <= 0 || !in_array($discount_type, ['percentage', 'flat']) || $min_spend < 0 || ($max_discount !== null && $max_discount < 0)) {
        $error = "Invalid input! Please fill in all fields correctly.";
    } else {
        try {
            // Insert voucher into DB
            $stmt = $pdo->prepare("INSERT INTO vouchers (code, discount_type, discount_value, min_spend, max_discount, expiration_date) 
                                   VALUES (:code, :discount_type, :discount_value, :min_spend, :max_discount, :expiration_date)");
            $stmt->execute([
                ':code' => $code,
                ':discount_type' => $discount_type,
                ':discount_value' => $discount_value,
                ':min_spend' => $min_spend,
                ':max_discount' => $max_discount,
                ':expiration_date' => $expiration_date
            ]);
            $success = "Voucher added successfully!";
        } catch (PDOException $e) {
            $error = "Error adding voucher: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Voucher</title>
    <link rel="stylesheet" href="include/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</head>
<body class="admin-page manage-products">

<?php include 'sidebar.php'; ?>

<div class="manage-products-container">
    <div class="manage-products-main">
        <h2>Add New Voucher</h2>

        <!-- Display success or error messages -->
        <?php if (isset($success)) echo "<p class='success-msg'>$success</p>"; ?>
        <?php if (isset($error)) echo "<p class='error-msg'>$error</p>"; ?>

        <!-- Voucher Form -->
        <form method="post" class="manage-products-form">
            <label for="code">Voucher Code:</label>
            <input type="text" name="code" id="code" required>

            <label for="discount_type">Discount Type:</label>
            <select name="discount_type" id="discount_type" required>
                <option value="percentage">Percentage (%)</option>
                <option value="flat">Flat Amount ($)</option>
            </select>

            <label for="discount_value">Discount Value:</label>
            <input type="number" name="discount_value" id="discount_value" step="0.01" required>

            <label for="min_spend">Minimum Spend ($):</label>
            <input type="number" name="min_spend" id="min_spend" step="0.01" value="0">

            <label for="max_discount">Maximum Discount (optional):</label>
            <input type="number" name="max_discount" id="max_discount" step="0.01">

            <label for="expiration_date">Expiration Date (optional):</label>
            <input type="date" name="expiration_date" id="expiration_date">

            <button type="submit">Add Voucher</button>
        </form>

        <a href="admin_dashboard.php" class="back-btn">Back to Dashboard</a>
    </div>
</div>

</body>
</html>
