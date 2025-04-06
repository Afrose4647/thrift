<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_POST['order_id'])) {
    header("Location: my_orders.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "madz_thrifts");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$order_id = $_POST['order_id'];
$user_id = $_SESSION['user_id'];

$sql = "UPDATE orders SET status = 'Cancelled' WHERE id = ? AND user_id = ? AND status = 'Pending'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $order_id, $user_id);
$stmt->execute();
$stmt->close();

header("Location: my_orders.php");
exit();
