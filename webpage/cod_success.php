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
$payment_method = 'COD';
$total_price = $_POST['total'];

// Insert into orders table
$stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status, address, payment_method, phone) 
                        VALUES (?, ?, 'Pending', ?, ?, ?)");
$stmt->bind_param("idsss", $user_id, $total_price, $address, $payment_method, $phone);
$stmt->execute();
$order_id = $stmt->insert_id;

// Insert each order item
foreach ($_SESSION['cart'] as $product_id => $item) {
    $stmt2 = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) 
                             VALUES (?, ?, ?, ?)");
    $stmt2->bind_param("iiii", $order_id, $product_id, $item['quantity'], $item['price']);
    $stmt2->execute();
}

// Clear cart
unset($_SESSION['cart']);

// ✅ Return redirect URL as plain text
echo "order_success.php";
exit();
?>
