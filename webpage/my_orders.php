<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "madz_thrifts");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Step 1: Get orders for this user
$sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_result = $stmt->get_result();
$orders = [];
while ($row = $order_result->fetch_assoc()) {
    $orders[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">My Orders</h2>

    <?php if (count($orders) === 0): ?>
        <div class="alert alert-info text-center">You haven't placed any orders yet.</div>
    <?php else: ?>
        <div class="accordion" id="orderAccordion">
        <?php foreach ($orders as $index => $order): ?>
            <div class="accordion-item mb-3">
                <h2 class="accordion-header" id="heading<?= $index ?>">
                    <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $index ?>">
                        Order #<?= $order['id'] ?> | ₹<?= $order['total_price'] ?> | <?= $order['status'] ?>
                    </button>
                </h2>
                <div id="collapse<?= $index ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#orderAccordion">
                    <div class="accordion-body">
                        <p><strong>Placed on:</strong> <?= $order['created_at'] ?></p>
                        <p><strong>Address:</strong> <?= $order['address'] ?></p>
                        <p><strong>Phone:</strong> <?= $order['phone'] ?></p>

                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Product Name</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            $order_id = $order['id'];
                            $item_sql = "SELECT oi.quantity, oi.price, p.name, p.image 
                                         FROM order_items oi
                                         INNER JOIN products p ON oi.product_id = p.id
                                         WHERE oi.order_id = ?";
                            $item_stmt = $conn->prepare($item_sql);
                            $item_stmt->bind_param("i", $order_id);
                            $item_stmt->execute();
                            $item_result = $item_stmt->get_result();
                            if ($item_result->num_rows === 0) {
                                echo '<tr><td colspan="4" class="text-center text-danger">No items found in this order.</td></tr>';
                            } else {
                                while ($item = $item_result->fetch_assoc()): ?>
                                    <tr>
                                        <td><img src="<?= htmlspecialchars($item['image']) ?>" width="60" height="60" alt="Product Image"></td>
                                        <td><?= htmlspecialchars($item['name']) ?></td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td>₹<?= $item['price'] ?></td>
                                    </tr>
                                <?php endwhile;
                            }
                            $item_stmt->close();
                            ?>
                            </tbody>
                        </table>

                        <?php if ($order['status'] === 'Pending'): ?>
                        <form method="POST" action="cancel_order.php" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-danger">Cancel Order</button>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
