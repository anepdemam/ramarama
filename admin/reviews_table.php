<?php
include('db.php');

$product_id = $_GET['product_id'] ?? null;
if (!$product_id || !is_numeric($product_id)) {
    die("Invalid or missing product ID.");
}

// Pagination settings
$perPage = 5;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

// Sorting settings
$allowedSortBy = ['username', 'rating', 'date'];
$allowedSortOrder = ['asc', 'desc'];

$sortBy = in_array($_GET['sort_by'] ?? '', $allowedSortBy) ? $_GET['sort_by'] : 'date';
$sortOrder = in_array(strtolower($_GET['sort_order'] ?? ''), $allowedSortOrder) ? strtolower($_GET['sort_order']) : 'desc';

// Map sort keys to database columns
$sortColumns = [
    'username' => 'u.username',
    'rating' => 'r.rating',
    'date' => 'r.created_at'
];
$orderBy = $sortColumns[$sortBy];

// Count total reviews for pagination
$countStmt = $pdo->prepare("SELECT COUNT(*) FROM reviews r WHERE r.product_id = :product_id");
$countStmt->execute(['product_id' => $product_id]);
$totalReviews = $countStmt->fetchColumn();
$totalPages = ceil($totalReviews / $perPage);

// Fetch reviews with pagination and sorting
$sql = "
    SELECT r.*, u.username 
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    WHERE r.product_id = :product_id
    ORDER BY $orderBy $sortOrder
    LIMIT :offset, :perpage
";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':product_id', $product_id, PDO::PARAM_INT);
stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':perpage', $perPage, PDO::PARAM_INT);
$stmt->execute();
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper function to build sorting links preserving pagination and other params
function buildSortLink($column, $currentSortBy, $currentSortOrder, $product_id, $page) {
    $order = 'asc';
    if ($currentSortBy === $column) {
        $order = $currentSortOrder === 'asc' ? 'desc' : 'asc';
    }
    return "?product_id={$product_id}&page={$page}&sort_by={$column}&sort_order={$order}";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Product Reviews</title>
    <link rel="stylesheet" href="include/styles.css">
</head>
<body>
    <div class="review">
        <h2>Reviews for Product ID <?= htmlspecialchars($product_id) ?></h2>

        <?php if ($totalReviews == 0): ?>
            <p class="no-reviews">No reviews yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th scope="col"><a href="<?= buildSortLink('username', $sortBy, $sortOrder, $product_id, $page) ?>" title="Sort by Username"><?= 'Username' ?><?php if ($sortBy === 'username') echo $sortOrder === 'asc' ? ' ▲' : ' ▼'; ?></a></th>
                        <th scope="col"><a href="<?= buildSortLink('rating', $sortBy, $sortOrder, $product_id, $page) ?>" title="Sort by Rating"><?= 'Rating' ?><?php if ($sortBy === 'rating') echo $sortOrder === 'asc' ? ' ▲' : ' ▼'; ?></a></th>
                        <th scope="col">Comment</th>
                        <th scope="col"><a href="<?= buildSortLink('date', $sortBy, $sortOrder, $product_id, $page) ?>" title="Sort by Date"><?= 'Date' ?><?php if ($sortBy === 'date') echo $sortOrder === 'asc' ? ' ▲' : ' ▼'; ?></a></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $review): ?>
                    <tr>
                        <td><?= htmlspecialchars($review['username']) ?></td>
                        <td><?= (int)$review['rating'] ?>/5</td>
                        <td><?= nl2br(htmlspecialchars($review['comment'])) ?></td>
                        <td><?= date('F j, Y, g:i a', strtotime($review['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pagination" role="navigation" aria-label="Pagination">
                <?php if ($page > 1): ?>
                    <a href="?product_id=<?= $product_id ?>&page=<?= $page - 1 ?>&sort_by=<?= $sortBy ?>&sort_order=<?= $sortOrder ?>" aria-label="Previous page">&laquo; Prev</a>
                <?php endif; ?>

                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                    <a href="?product_id=<?= $product_id ?>&page=<?= $p ?>&sort_by=<?= $sortBy ?>&sort_order=<?= $sortOrder ?>" class="<?= $p === $page ? 'current' : '' ?>" aria-current="<?= $p === $page ? 'page' : 'false' ?>"><?= $p ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?product_id=<?= $product_id ?>&page=<?= $page + 1 ?>&sort_by=<?= $sortBy ?>&sort_order=<?= $sortOrder ?>" aria-label="Next page">Next &raquo;</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
