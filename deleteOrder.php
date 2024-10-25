<?php
session_start();
require_once '../core/dbConfig.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../sql/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];

    $stmt = $pdo->prepare("DELETE FROM orders WHERE order_id = ? AND added_by = ?");
    if ($stmt->execute([$orderId, $_SESSION['user_id']])) {
        header("Location: orders.php");
        exit;
    } else {
        echo "Error deleting order.";
    }
} else {
    echo "No order ID specified.";
}
