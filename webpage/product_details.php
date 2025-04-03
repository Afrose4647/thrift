<?php
include 'db.php';
session_start();

if (!isset($_GET['id'])) {
    die("Product not found.");
}

$product_id = intval($_GET['id']);
$result = $conn->query("SELECT * FROM products WHERE id = $product_id");
$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= htmlspecialchars($product['name']); ?> - Madz Thriftz</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Product Details -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6">
                <img src="<?= htmlspecialchars($product['image']); ?>" class="img-fluid" alt="<?= htmlspecialchars($product['name']); ?>">
            </div>
            <div class="col-md-6">
                <h2><?= htmlspecialchars($product['name']); ?></h2>
                <h4>₹<?= number_format($product['price'], 2); ?></h4>
                <p><?= htmlspecialchars($product['description']); ?></p>
                <a href="cart.php?action=add&id=<?= $product['id']; ?>" class="btn btn-success">Add to Cart</a>
                <a href="index.php" class="btn btn-secondary">Back to Shop</a>
            </div>
        </div>
    </div>

</body>
</html>
