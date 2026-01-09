<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'customer') {
    header('Location: login.php');
    exit;
}
?>

<!-- Include Header -->
<?php include('header.php'); ?>

<?php include('slide.php'); ?>

<!-- Hero Section -->
<section class="hero" id="home">
    <div class="hero-content">
        <h1>Welcome to Our Store</h1>
        <p>Discover the latest products and enjoy great deals!</p>
        <a href="products.php" class="btn">Shop Now</a>
    </div>
</section>

<?php include('cornelius.php'); ?>
<!-- Include Footer -->
<?php include('footer.php'); ?>
