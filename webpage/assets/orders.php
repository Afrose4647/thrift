<?php
include '../db.php';
session_start();

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

$orders = $conn->query("SELECT * FROM orders");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_status'])) {
    $order_id = $_POST['order_id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('Order status updated!'); window.location.href='orders.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin - Manage Orders</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

    <div class="container mt-4">
        <h2>Manage Orders</h2>
        <table class="table">
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $orders->fetch_assoc()) { ?>
            <tr>
                <td><?= $row['id']; ?></td>
                <td><?= $row['user_id']; ?></td>
                <td>$<?= $row['total_price']; ?></td>
                <td><?= $row['status']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="order_id" value="<?= $row['id']; ?>">
                        <select name="status">
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Shipped">Shipped</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                        <button type="submit" name="update_status" class="btn btn-success btn-sm">Update</button>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>
