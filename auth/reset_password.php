<?php
session_start();
require 'db.php';

$error = '';
$success = '';
$show_form = false;

// Get token from URL
$token = $_GET['token'] ?? '';

if (!$token) {
    $error = 'Invalid or missing token.';
} else {
    // Check if token is valid and not expired
    $stmt = $pdo->prepare("SELECT email, expires_at FROM password_resets WHERE token = :token");
    $stmt->execute(['token' => $token]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        $error = 'Invalid token.';
    } elseif (strtotime($row['expires_at']) < time()) {
        $error = 'Token has expired.';
    } else {
        $show_form = true;
        $email = $row['email'];
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $show_form) {
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($password) || empty($confirm_password)) {
        $error = 'Please fill out all fields.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        // Hash password and update user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = :email");
        $stmt->execute(['password' => $hashed_password, 'email' => $email]);

        // Delete used token
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE token = :token");
        $stmt->execute(['token' => $token]);

        $success = 'Password successfully reset. You can now <a href="login.php">login</a>.';
        $show_form = false;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Reset Password</title>
<link rel="stylesheet" href="include/styles.css" />
</head>
<body class="login-page">
<div class="login-container">
    <h2>Reset Password</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php endif; ?>

    <?php if ($show_form): ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password" required />
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm New Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required />
            </div>
            <button type="submit" class="btn">Reset Password</button>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
