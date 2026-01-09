<?php
session_start();
require 'db.php';       // Your PDO connection setup
require 'mailer.php';   // The mailer function above

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        $error = 'Please enter your email address.';
    } else {
        // Check if user exists
        $stmt = $pdo->prepare("SELECT id, username, email FROM users WHERE email = :email");
		$stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Generate a secure token
            $token = bin2hex(random_bytes(32));

            // Save token in password_resets table with expiry (e.g., 1 hour)
            $expires_at = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Delete any previous tokens for this email (optional)
            $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = :email");
            $stmt->execute(['email' => $email]);

            // Insert new token
            $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (:email, :token, :expires_at)");
            $stmt->execute([
                'email' => $email,
                'token' => $token,
                'expires_at' => $expires_at
            ]);

            // Send reset email
            if (sendResetEmail($email, $token)) {
                $success = 'Password reset link sent to your email.';
            } else {
                $error = 'Failed to send reset email. Please try again later.';
            }
        } else {
            $error = 'No account found with that email.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Forgot Password</title>
<link rel="stylesheet" href="include/styles.css" />
</head>
<body class="login-page">
<div class="login-container">
    <h2>Forgot Password</h2>

    <form method="POST" action="forgot_password.php">
        <div class="form-group">
            <label for="email">Enter your registered email address:</label>
            <input type="email" id="email" name="email" required />
        </div>
        <button type="submit" class="btn">Send Reset Link</button>
    </form>
	
	<?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <p><a href="login.php">Back to Login</a></p>
</div>
</body>
</html>
