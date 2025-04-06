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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .table th {
            background: #343a40;
            color: #fff;
            text-align: center;
        }
        .status-badge {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-shipped { background-color: #17a2b8; color: #fff; }
        .status-delivered { background-color: #28a745; color: #fff; }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mb-4">📦 Manage Orders</h2>

    <a href="dashboard.php" class="btn btn-dark mb-3">⬅ Back to Dashboard</a>

    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($order = $orders->fetch_assoc()): ?>
            <tr>
                <td><?= $order['id']; ?></td>
                <td><?= $order['user_id']; ?></td>
                <td>₹<?= number_format($order['total_price'], 2); ?></td>
                <td>
                    <span class="status-badge 
                        <?= $order['status'] == 'Pending' ? 'status-pending' : 
                           ($order['status'] == 'Shipped' ? 'status-shipped' : 'status-delivered'); ?>">
                        <?= $order['status']; ?>
                    </span>
                </td>
                <td>
                    <a href="update_order.php?id=<?= $order['id']; ?>&status=Shipped" class="btn btn-info btn-sm">🚚 Mark Shipped</a>
                    <a href="update_order.php?id=<?= $order['id']; ?>&status=Delivered" class="btn btn-success btn-sm">✅ Mark Delivered</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
