<?php
include '../db.php';
session_start();
$user_id = $_SESSION['user_id'] ?? 0;

$orders = $conn->query("SELECT * FROM orders WHERE user_id = $user_id ORDER BY id DESC");
?>

<h2>My Orders</h2>
<?php while ($order = $orders->fetch_assoc()): ?>
    <p>Order #<?= $order['id']; ?> - Status: <?= $order['status']; ?></p>
<?php endwhile; ?>
