<?php
session_start();
include '../db.php';
require 'send_email.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = intval($_GET['id']);
    $status = $_GET['status'];

    // ✅ Secure the update query
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();

    // ✅ Get user email securely
    $stmt = $conn->prepare("SELECT user_id FROM orders WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    // Get user email
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($user_email);
    $stmt->fetch();
    $stmt->close();

    // ✅ Send Order Status Update Email
    if ($user_email) {
        $subject = "Order Status Updated - Madz Thriftz";
        $body = "<h2>Your order status has changed!</h2>
                 <p>Order ID: <b>$id</b></p>
                 <p>New Status: <b style='color:blue;'>$status</b></p>
                 <p>Thank you for shopping with us!</p>";

        sendEmail($user_email, $subject, $body);
    }

    // ✅ Redirect with success message
    $_SESSION['message'] = "Order #$id has been updated to '$status'.";
    header("Location: manage_orders.php");
    exit();
}

// Redirect back if no ID or status provided
header("Location: manage_orders.php");
exit();
?>
