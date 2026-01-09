<?php
if (!isset($id) || !is_numeric($id)) {
    echo "<p>Invalid product ID for reviews.</p>";
    return;
}

$perPage = 5; // reviews per page
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// Count total reviews for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE product_id = :id");
$countStmt->execute(['id' => $id]);
$totalReviews = $countStmt->fetchColumn();
$totalPages = ceil($totalReviews / $perPage);

$review_stmt = $pdo->prepare("
    SELECT r.*, u.username 
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = :id
    ORDER BY r.created_at DESC
    LIMIT :offset, :perpage
");
$review_stmt->bindParam(':id', $id, PDO::PARAM_INT);
$review_stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$review_stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
$review_stmt->execute();
$reviews = $review_stmt->fetchAll(PDO::FETCH_ASSOC);

function renderStars($rating) {
    $fullStar = "&#9733;";  // ★
    $emptyStar = "&#9734;"; // ☆
    $rating = (int)$rating;
    $stars = str_repeat($fullStar, $rating) . str_repeat($emptyStar, 5 - $rating);
    return "<span class='star-rating' aria-label='Rating: {$rating} out of 5'>{$stars}</span>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Product Reviews</title>
    <link rel="stylesheet" href="include/review-styles.css">
</head>
<body>
    <div class="review">
        <h3>Customer Reviews</h3>

        <?php if (empty($reviews)): ?>
            <p>No reviews yet. Be the first to review this product!</p>
        <?php else: ?>
            <ul>
                <?php foreach ($reviews as $review):
                    $reviewDate = date('F j, Y, g:i a', strtotime($review['created_at']));
                ?>
                    <li>
                        <strong><?= htmlspecialchars($review['username']); ?></strong>
                        <?= renderStars($review['rating']); ?><br>
                        <p><?= nl2br(htmlspecialchars($review['comment'])); ?></p>
                        <small>Reviewed on <?= $reviewDate; ?></small>
                    </li>
                <?php endforeach; ?>
            </ul>

            <nav class="pagination" aria-label="Reviews Pagination">
                <?php if ($page > 1): ?>
                    <a href="?id=<?= $id ?>&page=<?= $page - 1 ?>" aria-label="Previous page">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <a href="?id=<?= $id ?>&page=<?= $p ?>" class="<?= $p === $page ? 'current' : '' ?>" aria-current="<?= $p === $page ? 'page' : 'false' ?>">
                        <?= $p ?>
                    </a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?id=<?= $id ?>&page=<?= $page + 1 ?>" aria-label="Next page">Next &raquo;</a>
                <?php endif; ?>
            </nav>
        <?php endif; ?>
    </div>
</body>
</html>
