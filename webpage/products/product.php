<?php
include '../db.php';
session_start();

if (!isset($_GET['id'])) {
    echo "Product not found!";
    exit();
}

$product_id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();
$stmt->close();

if (!$product) {
    echo "Product not found!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $product['name']; ?> - Madz Thriftz</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <nav class="navbar">
        <a href="../index.php">Home</a>
        <a href="index.php">Shop</a>
        <a href="../cart/index.php">Cart</a>
        <a href="../orders/index.php">My Orders</a>
    </nav>

    <div class="container">
        <div class="product-details">
            <img src="<?= $product['image_url']; ?>" alt="<?= $product['name']; ?>">
            <h2><?= $product['name']; ?></h2>
            <p>₹<?= $product['price']; ?></p>
            <p><?= $product['description']; ?></p>
            <button class="add-to-cart" data-id="<?= $product['id']; ?>">Add to Cart</button>
        </div>
    </div>

    <script src="../assets/js/scripts.js"></script>

</body>
</html>
