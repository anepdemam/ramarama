<?php 
session_start(); // Start the session at the top
include('includes/db.php'); // Include the database connection

// Redirect based on user role if session variable 'user_role' is set
if (isset($_SESSION['user_role'])) {
    if ($_SESSION['user_role'] === 'admin') {
        header('Location: admin/index.php'); // Redirect to admin page
        exit;
    } elseif ($_SESSION['user_role'] === 'customer') {
        header('Location: customer.php'); // Redirect to customer page
        exit;
    }
}

// Fetch products using PDO
$sql = "SELECT * FROM products";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all products as an associative array

// Check if any products were found
if (!$products) {
    die("No products found.");
}
?>

<!-- Include Header -->
<?php include('includes/header.php'); ?>

<?php include 'includes/slide.php'; ?>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-content">
        <h1>Welcome to Our Store</h1>
        <p>Discover the latest products and enjoy great deals!</p>
        <a href="products.php" class="btn">Shop Now</a>
    </div>
</section>



<?php include('includes/chat_widget.php'); ?>

<?php include('includes/footer.php'); ?>
