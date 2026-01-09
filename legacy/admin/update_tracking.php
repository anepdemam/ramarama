<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['tracking_number'])) {
    $order_id = $_POST['order_id'];
    $tracking_number = trim($_POST['tracking_number']);

    try {
        $stmt = $pdo->prepare("UPDATE orders SET tracking_number = :tracking_number WHERE id = :order_id");
        $stmt->execute([
            'tracking_number' => $tracking_number,
            'order_id' => $order_id
        ]);
        header("Location: manage_orders.php?success=1");
        exit;
    } catch (PDOException $e) {
        error_log("Update tracking failed: " . $e->getMessage());
        header("Location: manage_orders.php?error=1");
        exit;
    }
} else {
    header("Location: manage_orders.php");
    exit;
}
