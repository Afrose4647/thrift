<?php
include '../db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Fetch order status
$stmt = $conn->prepare("SELECT id, status, created_at FROM orders WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if (!$order) {
    echo "<script>alert('Order not found!'); window.location.href='index.php';</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Track Order</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

    <nav class="navbar">
        <a href="../index.php">Home</a>
        <a href="../cart/index.php">Cart</a>
        <a href="index.php">My Orders</a>
        <a href="../auth/logout.php">Logout</a>
    </nav>

    <div class="container mt-4">
        <h2>Track Order</h2>
        <div class="card">
            <p><strong>Order ID:</strong> #<?php echo htmlspecialchars($order['id']); ?></p>
            <p><strong>Status:</strong> <?php echo htmlspecialchars($order['status']); ?></p>
            <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order['created_at']); ?></p>

            <h4>Order Progress:</h4>
            <div class="progress">
                <?php
                $statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
                $currentStatus = array_search($order['status'], $statuses);
                foreach ($statuses as $index => $status) {
                    $progressClass = ($index <= $currentStatus) ? 'bg-success' : 'bg-light';
                    echo "<div class='progress-bar $progressClass' style='width:20%'>$status</div>";
                }
                ?>
            </div>
        </div>
    </div>

</body>
</html>
