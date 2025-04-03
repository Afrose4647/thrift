<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$order = $conn->query("SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id")->fetch_assoc();
$order_items = $conn->query("SELECT p.name, oi.quantity, oi.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = $order_id");

if (!$order) {
    echo "Order not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Order Details - Madz Thriftz</title>
</head>
<body>

    <h2>Order Details</h2>
    <p><b>Order ID:</b> <?= $order['id']; ?></p>
    <p><b>Total Price:</b> ₹<?= $order['total_price']; ?></p>
    <p><b>Status:</b> <?= $order['status']; ?></p>

    <h3>Items:</h3>
    <table border="1">
        <tr>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
        </tr>
        <?php while ($item = $order_items->fetch_assoc()): ?>
        <tr>
            <td><?= $item['name']; ?></td>
            <td><?= $item['quantity']; ?></td>
            <td>₹<?= $item['price']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <p><a href="orders.php">Back to Orders</a></p>

</body>
</html>
