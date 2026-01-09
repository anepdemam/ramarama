<?php
session_start();  // Start the session

// CSRF Token generation
function generateCSRFToken()
{
    return bin2hex(random_bytes(32));  // Generate a secure random token
}

// Generate and store the CSRF token in the session
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = generateCSRFToken();
}

$csrf_token = $_SESSION['csrf_token'];  // Store the CSRF token for the form

include('../includes/db.php');  // Include the database connection

// Check if user is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Handle product deletion
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    try {
        $stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->bindParam(':id', $deleteId, PDO::PARAM_INT);
        $stmt->execute();
        header('Location: manage_products.php');
        exit;
    } catch (PDOException $e) {
        echo 'Error deleting product: ' . $e->getMessage();
    }
}

// Handle product editing (fetch details if in edit mode)
$productToEdit = null;
$editMode = isset($_GET['edit']);
if ($editMode) {
    $editId = $_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $editId, PDO::PARAM_INT);
    $stmt->execute();
    $productToEdit = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Default values for form fields if no product is being edited
$name = $editMode ? htmlspecialchars($productToEdit['name']) : '';
$description = $editMode ? htmlspecialchars($productToEdit['description']) : '';
$price = $editMode ? $productToEdit['price'] : '';
$category = $editMode ? htmlspecialchars($productToEdit['category']) : '';
$stock_small = $editMode ? $productToEdit['stock_small'] : '';
$stock_medium = $editMode ? $productToEdit['stock_medium'] : '';
$stock_large = $editMode ? $productToEdit['stock_large'] : '';
$stock_xl = $editMode ? $productToEdit['stock_xl'] : '';
$stock_2xl = $editMode ? $productToEdit['stock_2xl'] : '';
$images = $editMode ? json_decode($productToEdit['images'], true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF Token validation
    if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Invalid CSRF token');
    }

    // Collect form data
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $category = $_POST['category'];
    $stock_small = $_POST['stock_small'];
    $stock_medium = $_POST['stock_medium'];
    $stock_large = $_POST['stock_large'];
    $stock_xl = $_POST['stock_xl'];
    $stock_2xl = $_POST['stock_2xl'];

    // Handle image uploads
    $uploadedImages = [];
    if (!empty($_FILES['images']['name'][0])) {
        $uploadDir = '../uploads/';
        foreach ($_FILES['images']['name'] as $key => $imageName) {
            $imageTmpName = $_FILES['images']['tmp_name'][$key];
            $imageType = mime_content_type($imageTmpName);

            // Only allow images (JPEG, PNG, GIF)
            if (!in_array($imageType, ['image/jpeg', 'image/png', 'image/gif'])) {
                echo "Invalid image type: $imageName. Only JPEG, PNG, and GIF files are allowed.";
                exit;
            }

            $imagePath = $uploadDir . basename($imageName);
            if (move_uploaded_file($imageTmpName, $imagePath)) {
                $uploadedImages[] = $imagePath;
            } else {
                echo "Failed to upload $imageName.";
                exit;
            }
        }
    }

    if (isset($_POST['add_product'])) {
        // Add product
        try {
            $stmt = $pdo->prepare("INSERT INTO products (name, description, price, category, stock_small, stock_medium, stock_large, stock_xl, stock_2xl, images) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $description, $price, $category, $stock_small, $stock_medium, $stock_large, $stock_xl, $stock_2xl, json_encode($uploadedImages)]);
            header('Location: manage_products.php');
            exit;
        } catch (PDOException $e) {
            echo 'Error adding product: ' . $e->getMessage();
        }
    } elseif (isset($_POST['edit_product'])) {
        // Edit product
        $id = $_POST['id'];
        try {
            $stmt = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, category = ?, stock_small = ?, stock_medium = ?, stock_large = ?, stock_xl = ?, stock_2xl = ?, images = ? WHERE id = ?");
            $stmt->execute([$name, $description, $price, $category, $stock_small, $stock_medium, $stock_large, $stock_xl, $stock_2xl, json_encode($uploadedImages), $id]);
            header('Location: manage_products.php');
            exit;
        } catch (PDOException $e) {
            echo 'Error updating product: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Products</title>
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body class="admin-page manage-products">

    <?php include '../includes/sidebar.php'; ?>

    <div class="manage-products-container">
        <div class="manage-products-main">
            <h2><?= $editMode ? "Edit Product" : "Add Product"; ?></h2>

            <!-- Ensure CSRF Token is included in the form -->
            <form action="manage_products.php" method="POST" enctype="multipart/form-data" class="manage-products-form"
                name="add">
                <input type="hidden" name="csrf_token" value="<?= $csrf_token; ?>"> <!-- CSRF Token -->

                <?php if ($editMode): ?>
                    <input type="hidden" name="id" value="<?= $editId; ?>">
                <?php endif; ?>

                <!-- Form Fields for Product Details -->
                <label>Product Name:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($name); ?>" required><br>

                <label>Category:</label>
                <select name="category" required>
                    <option value="Tops" <?= $category == 'Tops' ? 'selected' : ''; ?>>Tops</option>
                    <option value="Hoodies" <?= $category == 'Hoodies' ? 'selected' : ''; ?>>Hoodies</option>
                    <option value="Bottoms" <?= $category == 'Bottoms' ? 'selected' : ''; ?>>Bottoms</option>
                    <option value="Outerwear" <?= $category == 'Outerwear' ? 'selected' : ''; ?>>Outerwear</option>
                </select><br>

                <label>Description:</label>
                <textarea name="description" required><?= htmlspecialchars($description); ?></textarea><br>

                <label>Price:</label>
                <input type="number" step="0.01" name="price" value="<?= $price; ?>" required><br>

                <!-- Stock Inputs -->
                <label>Stock (Small):</label>
                <input type="number" name="stock_small" value="<?= $stock_small; ?>" required><br>

                <label>Stock (Medium):</label>
                <input type="number" name="stock_medium" value="<?= $stock_medium; ?>" required><br>

                <label>Stock (Large):</label>
                <input type="number" name="stock_large" value="<?= $stock_large; ?>" required><br>

                <label>Stock (XL):</label>
                <input type="number" name="stock_xl" value="<?= $stock_xl; ?>" required><br>

                <label>Stock (2XL):</label>
                <input type="number" name="stock_2xl" value="<?= $stock_2xl; ?>" required><br>

                <label>Upload Images:</label>
                <input type="file" id="imageUpload" name="images[]" multiple accept="image/*"><br>
                <div id="previewContainer"></div>

                <button type="submit"
                    name="<?= $editMode ? "edit_product" : "add_product"; ?>"><?= $editMode ? "Update Product" : "Add Product"; ?></button>
                <?php if ($editMode): ?>
                    <a href="manage_products.php">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <!-- Product List -->
        <div class="manage-products-table-container">
            <h3>Product List</h3>
            <table class="manage-products-table" border="1">
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Images</th>
                    <th>Actions</th>
                </tr>
                <?php
                try {
                    $stmt = $pdo->query("SELECT * FROM products");
                    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo 'Error loading products: ' . $e->getMessage();
                }

                foreach ($products as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['name']); ?></td>
                        <td><?= htmlspecialchars($product['category']); ?></td>
                        <td><?= htmlspecialchars($product['description']); ?></td>
                        <td>RM<?= number_format($product['price'], 2); ?></td>
                        <td>
                            Small: <?= $product['stock_small']; ?><br>
                            Medium: <?= $product['stock_medium']; ?><br>
                            Large: <?= $product['stock_large']; ?><br>
                            XL: <?= $product['stock_xl']; ?><br>
                            2XL: <?= $product['stock_2xl']; ?>
                        </td>
                        <td>
                            <?php foreach (json_decode($product['images'], true) as $image): ?>
                                <img src="<?= htmlspecialchars($image); ?>" width="50">
                            <?php endforeach; ?>
                        </td>
                        <td>

                            <a href="manage_products.php?edit=<?= $product['id']; ?>" title="Edit">
                                <i class="fas fa-edit"></i> Edit
                            </a>|

                            <a href="manage_products.php?delete=<?= $product['id']; ?>" title="Remove"
                                onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
        document.getElementById('imageUpload').addEventListener('change', function (event) {
            let previewContainer = document.getElementById('previewContainer');
            previewContainer.innerHTML = ''; // Clear previous previews

            Array.from(event.target.files).forEach(file => {
                let reader = new FileReader();
                reader.onload = function (e) {
                    let img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = "100px";
                    img.style.margin = "5px";
                    previewContainer.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>

</body>

</html>