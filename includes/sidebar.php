<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="unique-admin-sidebar">
    <h2>Admin Panel</h2>
    <ul>
        <li><a href="index.php" class="<?= ($current_page == 'index.php') ? 'active' : ''; ?>">Dashboard</a></li>
        <li><a href="manage_products.php"
                class="<?= ($current_page == 'manage_products.php') ? 'active' : ''; ?>">Manage Products</a></li>
        <li><a href="add_voucher.php" class="<?= ($current_page == 'add_voucher.php') ? 'active' : ''; ?>">Manage
                Vouchers</a></li>
        <li><a href="order_logs.php" class="<?= ($current_page == 'order_logs.php') ? 'active' : ''; ?>">Order Logs</a>
        </li>
        <li><a href="users.php" class="<?= ($current_page == 'users.php') ? 'active' : ''; ?>">Users</a></li>
        <li><a href="../auth/logout.php">Logout</a></li>
    </ul>
</nav>