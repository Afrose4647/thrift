<?php
session_start();
if (!isset($_SESSION['user_id']) || !isset($_SESSION['cart'])) {
    echo "login.php";
    exit();
}

$conn = new mysqli("localhost", "root", "", "madz_thrifts");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$fullname = $_POST['fullname'];
$address = $_POST['address'];
$phone = $_POST['phone'];
$total_price = $_POST['total'];
$payment_method = "Online";

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, address, payment_method, phone) VALUES (?, ?, 'Completed', ?, ?, ?)");
$stmt->bind_param("idsss", $user_id, $total_price, $address, $payment_method, $phone);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert order items
foreach ($_SESSION['cart'] as $item) {
    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiii", $order_id, $item['id'], $item['quantity'], $item['price']);
    $stmt2->execute();
}

// Clear cart
unset($_SESSION['cart']);

// ✅ Return redirect page
echo "order_success.php";
exit();
?>
