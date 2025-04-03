<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

$orders = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manage Orders - Madz Thriftz</title>
</head>
<body>

    <h2>Manage Orders</h2>
    <a href="dashboard.php">Back to Dashboard</a>

    <table border="1">
        <tr>
            <th>Order ID</th>
            <th>User ID</th>
            <th>Total Price</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        <?php while ($order = $orders->fetch_assoc()): ?>
        <tr>
            <td><?= $order['id']; ?></td>
            <td><?= $order['user_id']; ?></td>
            <td>₹<?= $order['total_price']; ?></td>
            <td><?= $order['status']; ?></td>
            <td>
                <a href="update_order.php?id=<?= $order['id']; ?>&status=Shipped">Mark Shipped</a> |
                <a href="update_order.php?id=<?= $order['id']; ?>&status=Delivered">Mark Delivered</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</body>
</html>
