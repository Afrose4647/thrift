<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'] ?? 0;
    $name = $_POST['name'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    
    $conn->query("INSERT INTO orders (user_id, name, address, phone, status) VALUES ($user_id, '$name', '$address', '$phone', 'Pending')");
    $_SESSION['cart'] = []; // Empty cart after order
    header("Location: orders/index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout - Madz Thriftz</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>

    <h2>Checkout</h2>
    <form method="POST">
        <input type="text" name="name" required placeholder="Full Name">
        <input type="text" name="address" required placeholder="Shipping Address">
        <input type="text" name="phone" required placeholder="Phone Number">
        <button type="submit">Place Order</button>
    </form>

</body>
</html>
