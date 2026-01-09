<?php
include('db.php');

// Validate product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: products.php');
    exit;
}

$id = (int)$_GET['id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: products.php');
        exit;
    }

	$images = [];
	if (!empty($product['images'])) {
		$decoded = json_decode($product['images'], true);
		if (json_last_error() === JSON_ERROR_NONE) {
			$images = array_filter($decoded, function($img) {
				return is_string($img) && (file_exists($img) || filter_var($img, FILTER_VALIDATE_URL));
			});
		}
	}

    
    if (empty($images)) {
        $images = ['placeholder.jpg'];
    }

} catch (PDOException $e) {
    error_log('Error fetching product details: ' . $e->getMessage(), 3, 'errors.log');
    header('Location: products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title><?= htmlspecialchars($product['name']); ?></title>
<link rel="stylesheet" href="include/styles.css" />

<script>
    function updateStock() {
        const selectedSizeEl = document.querySelector('.size-option.selected');
        if (!selectedSizeEl || selectedSizeEl.classList.contains('out-of-stock')) {
            document.getElementById("stock-info").style.display = "none";
            const btn = document.getElementById("add-to-cart-button");
            btn.disabled = true;
            btn.style.backgroundColor = "grey";
            btn.style.cursor = "not-allowed";
            return;
        }

        const size = selectedSizeEl.dataset.size;
        const stockInfo = document.getElementById("stock-info");
        stockInfo.style.display = "none";

        let stockText = '';
        let stockStatus = 0;

        switch(size) {
            case "small":
                stockText = "Small: <?= ($product['stock_small'] > 0) ? $product['stock_small'] : 'Out of Stock'; ?>";
                stockStatus = <?= ($product['stock_small'] > 0) ? 1 : 0; ?>;
                break;
            case "medium":
                stockText = "Medium: <?= ($product['stock_medium'] > 0) ? $product['stock_medium'] : 'Out of Stock'; ?>";
                stockStatus = <?= ($product['stock_medium'] > 0) ? 1 : 0; ?>;
                break;
            case "large":
                stockText = "Large: <?= ($product['stock_large'] > 0) ? $product['stock_large'] : 'Out of Stock'; ?>";
                stockStatus = <?= ($product['stock_large'] > 0) ? 1 : 0; ?>;
                break;
            case "xl":
                stockText = "XL: <?= ($product['stock_xl'] > 0) ? $product['stock_xl'] : 'Out of Stock'; ?>";
                stockStatus = <?= ($product['stock_xl'] > 0) ? 1 : 0; ?>;
                break;
            case "2xl":
                stockText = "2XL: <?= ($product['stock_2xl'] > 0) ? $product['stock_2xl'] : 'Out of Stock'; ?>";
                stockStatus = <?= ($product['stock_2xl'] > 0) ? 1 : 0; ?>;
                break;
            default:
                stockText = '';
                stockStatus = 0;
        }

        if (stockText) {
            stockInfo.innerHTML = stockText;
            stockInfo.style.display = "block";
        }

        const addToCartButton = document.getElementById("add-to-cart-button");
        if (stockStatus === 0) {
            addToCartButton.disabled = true;
            addToCartButton.style.backgroundColor = "grey";
            addToCartButton.style.cursor = "not-allowed";
        } else {
            addToCartButton.disabled = false;
            addToCartButton.style.backgroundColor = "#4CAF50";
            addToCartButton.style.cursor = "pointer";
        }
    }

    function selectSize(el) {
        if (el.classList.contains('out-of-stock')) return;
        
        document.querySelectorAll('.size-option').forEach(opt => opt.classList.remove('selected'));
        el.classList.add('selected');
        document.getElementById('selected-size').textContent = el.textContent;
        document.getElementById('selected-size-input').value = el.dataset.size;
        updateStock();
    }

    document.addEventListener('DOMContentLoaded', () => {
        updateStock();
    });
</script>
</head>
<body>

<?php include 'header.php'; ?>

<div class="product-details-container">
    <h2><?= htmlspecialchars($product['name']); ?></h2>

    <div class="gallery-slider">
        <div class="prev" onclick="moveSlide(-1)">❮</div>
        <div class="gallery-wrapper">
            <?php foreach ($images as $image): ?>
                <div class="gallery-slide"><img src="<?= htmlspecialchars($image); ?>" alt="<?= htmlspecialchars($product['name']); ?>"></div>
            <?php endforeach; ?>
        </div>
        <div class="next" onclick="moveSlide(1)">❯</div>
    </div>

    <p><strong>Description:</strong> <?= nl2br(htmlspecialchars($product['description'])); ?></p>
    <p><strong>Price:</strong> RM<?= number_format($product['price'], 2); ?></p>

    <div class="size-label">Size: <span id="selected-size"></span></div>

    <div class="size-selector">
        <div class="size-option <?= $product['stock_small'] <= 0 ? 'out-of-stock' : '' ?>" 
             onclick="<?= $product['stock_small'] > 0 ? 'selectSize(this)' : '' ?>" 
             data-size="small">S</div>
        <div class="size-option <?= $product['stock_medium'] <= 0 ? 'out-of-stock' : '' ?>" 
             onclick="<?= $product['stock_medium'] > 0 ? 'selectSize(this)' : '' ?>" 
             data-size="medium">M</div>
        <div class="size-option <?= $product['stock_large'] <= 0 ? 'out-of-stock' : '' ?>" 
             onclick="<?= $product['stock_large'] > 0 ? 'selectSize(this)' : '' ?>" 
             data-size="large">L</div>
        <div class="size-option <?= $product['stock_xl'] <= 0 ? 'out-of-stock' : '' ?>" 
             onclick="<?= $product['stock_xl'] > 0 ? 'selectSize(this)' : '' ?>" 
             data-size="xl">XL</div>
        <div class="size-option <?= $product['stock_2xl'] <= 0 ? 'out-of-stock' : '' ?>" 
             onclick="<?= $product['stock_2xl'] > 0 ? 'selectSize(this)' : '' ?>" 
             data-size="2xl">XXL</div>
    </div>

    <div id="stock-info" style="display:none; margin-top: 10px;"></div> <br>

    <form action="cart.php" method="get">
        <input type="hidden" name="add_to_cart" value="<?= $product['id']; ?>">
        <input type="hidden" name="size" id="selected-size-input" value="">
        <input type="number" name="quantity" value="1" min="1" max="<?= max($product['stock_small'], $product['stock_medium'], $product['stock_large'], $product['stock_xl'], $product['stock_2xl']) ?>" required>
        <button type="submit" id="add-to-cart-button" class="add-to-cart-btn" disabled>Add to Cart</button>
    </form>

    <a href="products.php" class="back-btn">Back to Products</a>

    <?php include 'reviews_display.php'; ?>
</div>

<?php include 'footer.php'; ?>

</body>
</html>