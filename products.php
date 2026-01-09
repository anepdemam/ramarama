<?php
// Start the session
session_start();

// Include the database connection
include('includes/db.php');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get the search query, category, price, rating, and sort order from the URL
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$category = isset($_GET['category']) ? trim($_GET['category']) : '';
$price = isset($_GET['price']) ? $_GET['price'] : '';
$rating = isset($_GET['rating']) ? $_GET['rating'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : ''; // Sort order (low to high or high to low)

// Prepare the SQL query with search and category filtering
$sql = "SELECT * FROM products WHERE (name LIKE :search OR description LIKE :search)";

// If a category is selected, add a condition to the query
if (!empty($category)) {
    $sql .= " AND category = :category";
}

// If price is selected, add a condition to the query
if (!empty($price)) {
    $sql .= " AND price <= :price";
}

// If rating is selected, add a condition to the query
if (!empty($rating)) {
    $sql .= " AND rating >= :rating";
}

// If sorting is selected, add an ORDER BY clause to the query
if ($sort === 'low_to_high') {
    $sql .= " ORDER BY price ASC";
} elseif ($sort === 'high_to_low') {
    $sql .= " ORDER BY price DESC";
}

$stmt = $pdo->prepare($sql);

// Bind the search parameter with wildcards for partial matching
$searchTerm = "%$search%";
$stmt->bindParam(':search', $searchTerm, PDO::PARAM_STR);

// Bind the category, price, and rating parameters if they are provided
if (!empty($category)) {
    $stmt->bindParam(':category', $category, PDO::PARAM_STR);
}
if (!empty($price)) {
    $stmt->bindParam(':price', $price, PDO::PARAM_INT);
}
if (!empty($rating)) {
    $stmt->bindParam(':rating', $rating, PDO::PARAM_INT);
}

// Execute the query
$stmt->execute();

// Fetch all products
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get all categories for the dropdown (assuming you have a table with categories)
$category_stmt = $pdo->prepare("SELECT DISTINCT category FROM products");
$category_stmt->execute();
$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// Include Header
include('includes/header.php');
?>

<!-- Search and Filter Form -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<form method="GET" action="products.php" class="search-form">
    <h1>Our Products</h1>
    
    <!-- Search section -->
    <div class="search-section">
        <input type="text" id="search" name="search" placeholder="Search products..." value="<?php echo htmlspecialchars($search); ?>" autocomplete="off">
        <button type="submit">Search</button>
        
        <!-- Filter icon button -->
<button type="button" id="filter-icon" class="filter-icon">
    <i class="fas fa-filter"></i> <!-- Filter icon -->
</button>

        <!-- Filter dropdown -->
        <div id="filter-dropdown" class="filter-dropdown" style="display: none;">
            <!-- Category filter -->
            <select name="category">
                <option value="">All Categories</option>
                <?php 
                foreach ($categories as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat['category']); ?>" 
                        <?php echo ($category == $cat['category']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat['category']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Sort filter -->
            <select name="sort">
                <option value="">Sort By</option>
                <option value="low_to_high" <?php echo ($sort == 'low_to_high') ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="high_to_low" <?php echo ($sort == 'high_to_low') ? 'selected' : ''; ?>>Price: High to Low</option>
            </select>
			
            <!-- Submit filter button -->
            <button type="submit">Apply Filters</button>
        </div>
    </div>
</form>

<!-- Autocomplete Results (hidden by default) -->
<ul id="autocomplete-results" style="display: none;"></ul>

<!-- Product Grid -->
<section class="products" id="products">
    <div class="product-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <?php
                    // Assuming the images are stored in JSON format
                    $images = json_decode($product['images'], true);
                    $firstImage = !empty($images[0]) ? htmlspecialchars($images[0]) : 'placeholder.jpg';
                    $secondImage = !empty($images[1]) ? htmlspecialchars($images[1]) : $firstImage; // Use first image if second is missing
                    ?>

                    <div class="product-image-container">
                        <img src="<?= $firstImage; ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                        <img src="<?= $secondImage; ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="hover-image">
                    </div>

                    <div class="product-info">
                        <h3><?= htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description"><?= htmlspecialchars($product['description']); ?></p>
                        <p class="product-price">RM<?= number_format($product['price'], 2); ?></p>
                        <a href="viewdetails.php?id=<?= $product['id']; ?>" class="btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <h3>No products found matching your search or filters!</h3>
        <?php endif; ?>
    </div>
</section>

<!-- Include Footer -->
<?php include('includes/footer.php'); ?>

<!-- JavaScript for Predictive Search and Filter Toggle -->
<script>
    // Get the search input field, results container, filter button, and dropdown
    const searchInput = document.getElementById('search');
    const resultsContainer = document.getElementById('autocomplete-results');
    const filterIcon = document.getElementById('filter-icon');
    const filterDropdown = document.getElementById('filter-dropdown');

    // Add an event listener for keyup (every time user types)
    searchInput.addEventListener('keyup', function() {
        const query = searchInput.value.trim();

        if (query.length > 0) {
            // Send an AJAX request to the search_suggestions.php file
            fetch('search_suggestions.php?query=' + query)
                .then(response => response.json())
                .then(data => {
                    // Show the results container
                    resultsContainer.style.display = 'block';
                    resultsContainer.innerHTML = '';

                    // If there are suggestions, display them
                    if (data.length > 0) {
                        data.forEach(item => {
                            const listItem = document.createElement('li');
                            listItem.textContent = item;
                            resultsContainer.appendChild(listItem);
                        });
                    } else {
                        resultsContainer.innerHTML = '<li>No results found</li>';
                    }
                })
                .catch(error => console.error('Error fetching suggestions:', error));
        } else {
            // Hide results if the input is empty
            resultsContainer.style.display = 'none';
        }
    });

    // Toggle filter dropdown visibility when clicking the filter icon
    filterIcon.addEventListener('click', function() {
        filterDropdown.style.display = (filterDropdown.style.display === 'block') ? 'none' : 'block';
    });

    // Close the results and dropdown when clicking outside
    document.addEventListener('click', function(event) {
        if (!filterDropdown.contains(event.target) && event.target !== filterIcon) {
            filterDropdown.style.display = 'none';
        }
        if (!resultsContainer.contains(event.target) && event.target !== searchInput) {
            resultsContainer.style.display = 'none';
        }
    });
</script>
