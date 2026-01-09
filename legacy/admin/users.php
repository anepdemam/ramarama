<?php
session_start();
require 'db.php';

// Simple admin check
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$message = '';

// Handle form submission to add user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? 'customer';
    $email = trim($_POST['email'] ?? '');

    if (empty($username) || empty($password) || !in_array($role, ['admin', 'customer'])) {
        $message = "Please fill all required fields with valid data.";
    } else {
        // Check username exists
        $stmtCheck = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmtCheck->execute([$username]);
        if ($stmtCheck->fetchColumn() > 0) {
            $message = "Username already exists.";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role, email) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashedPassword, $role, $email]);
            $message = "User added successfully.";
        }
    }
}

// Fetch all users
$stmtUsers = $pdo->query("SELECT id, username, role, email, created_at FROM users ORDER BY created_at DESC");
$users = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>User Management</title>
<link rel="stylesheet" href="include/styles.css" />
</head>
<body class="admin-page manage-products">

<?php include 'sidebar.php'; ?>

<div class="manage-products-container">
    <h2>Users List</h2>

    <?php if ($message): ?>
        <p class="<?= strpos($message, 'successfully') !== false ? 'message' : 'error' ?>">
            <?= htmlspecialchars($message) ?>
        </p>
    <?php endif; ?>

    <form method="POST" class="user-add-form" style="margin-bottom:20px;">
        <label>
            Username*:
            <input type="text" name="username" required>
        </label>
        <label>
            Password*:
            <input type="password" name="password" required>
        </label>
        <label>
            Role*:
            <select name="role" required>
                <option value="customer" selected>Customer</option>
                <option value="admin">Admin</option>
            </select>
        </label>
        <label>
            Email:
            <input type="email" name="email">
        </label>
        <button type="submit" class="btn-submit" style="margin-top:10px;">Add User</button>
    </form>

    <?php if (empty($users)): ?>
        <p>No users found.</p>
    <?php else: ?>
        <table class="manage-products-table" border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Email</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['email'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="admin_dashboard.php" class="btn-back">Back to Admin Dashboard</a></p>
</div>

</body>
</html>
