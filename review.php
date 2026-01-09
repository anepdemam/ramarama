<?php
session_start();
require 'db.php';

// Check if user is logged in and role is customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'customer') {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$order_id = $_GET['order_id'] ?? null;
$product_id = $_GET['product_id'] ?? null;

if (!$order_id || !$product_id) {
    echo "Missing order ID or product ID.";
    exit;
}

// Verify the order belongs to the user
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :order_id AND user_id = :user_id");
$stmt->execute(['order_id' => $order_id, 'user_id' => $user_id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    echo "Order not found or access denied.";
    exit;
}

// Verify product is in the order
$stmt = $pdo->prepare("SELECT oi.*, p.name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = :order_id AND oi.product_id = :product_id");
$stmt->execute(['order_id' => $order_id, 'product_id' => $product_id]);
$item = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$item) {
    echo "Product not found in this order.";
    exit;
}

// Handle review submission
$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'] ?? null;
    $comment = trim($_POST['comment'] ?? '');

    if ($rating && in_array($rating, ['1','2','3','4','5'])) {
        // Check if user already reviewed this product in this order (optional)
        $stmt = $pdo->prepare("SELECT id FROM reviews WHERE user_id = :user_id AND product_id = :product_id AND order_id = :order_id");
        $stmt->execute([
            'user_id' => $user_id,
            'product_id' => $product_id,
            'order_id' => $order_id
        ]);
        $existing_review = $stmt->fetch();

        if ($existing_review) {
            $error_message = "You have already reviewed this product for this order.";
        } else {
            // Insert review
            $stmt = $pdo->prepare("INSERT INTO reviews (user_id, product_id, order_id, rating, comment, created_at) VALUES (:user_id, :product_id, :order_id, :rating, :comment, NOW())");
            $stmt->execute([
                'user_id' => $user_id,
                'product_id' => $product_id,
                'order_id' => $order_id,
                'rating' => $rating,
                'comment' => $comment
            ]);
            $success_message = "Thank you! Your review has been submitted.";
        }
    } else {
        $error_message = "Please provide a valid rating.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Review Product - <?= htmlspecialchars($item['name']) ?></title>
    <link rel="stylesheet" href="include/styles.css">
    <link rel="stylesheet" href="include/review-styles.css">
</head>
<body>
<?php include 'header.php'; ?>

<div class="product-details-container review-form">
    <h2>Review Product: <?= htmlspecialchars($item['name']) ?></h2>

    <?php if ($success_message): ?>
        <p class="success-message"><?= htmlspecialchars($success_message) ?></p>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <p class="error-message"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>

    <?php if (!$success_message): // Show form only if not yet submitted successfully ?>
    <form method="POST" action="">
        <label for="rating">Rating:</label>
        <select id="rating" name="rating" required>
            <option value="">Select rating</option>
            <option value="1">1 - Poor</option>
            <option value="2">2 - Fair</option>
            <option value="3">3 - Good</option>
            <option value="4">4 - Very Good</option>
            <option value="5">5 - Excellent</option>
        </select>

        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" placeholder="Write your review here..."></textarea>

        <button type="submit">Submit Review</button>
    </form>
    <?php endif; ?>

    <p><a href="customer.php"=>Back to Homepage</a></p>
</div>

<?php include 'footer.php'; ?>
</body>
</html>
