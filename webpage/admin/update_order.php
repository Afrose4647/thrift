<?php
session_start();
include '../db.php';
require 'send_email.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id = $_GET['id'];
    $status = $_GET['status'];

    $conn->query("UPDATE orders SET status = '$status' WHERE id = $id");

    // Get user email
    $order = $conn->query("SELECT user_id FROM orders WHERE id = $id")->fetch_assoc();
    $user_id = $order['user_id'];
    $user = $conn->query("SELECT email FROM users WHERE id = $user_id")->fetch_assoc();
    $user_email = $user['email'];

    // Send Order Status Update Email
    $subject = "Order Status Updated - Madz Thriftz";
    $body = "<h2>Your order status has changed!</h2>
             <p>Your order ID: <b>$id</b></p>
             <p>New Status: <b>$status</b></p>";

    sendEmail($user_email, $subject, $body);
}

header("Location: dashboard.php");
?>
