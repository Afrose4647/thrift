<?php
include 'db.php';
session_start();

// Fetch Products from Database
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Madz Thriftz - Home</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <?php include 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <header class="hero text-center py-5">
        <h1>Welcome to Madz Thriftz</h1>
        <p>Best collection of football jerseys, baggy jeans, and jackets</p>
    </header>

    <!-- Product Listing -->
    <div class="container mt-4">
        <h2 class="text-center">Featured Products</h2>
        <div class="row">
            <?php while ($product = $products->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card mb-3">
                        <!-- Display ImgBB Image -->
                        <img src="<?= htmlspecialchars($product['image']); ?>" class="card-img-top"
                             alt="<?= htmlspecialchars($product['name']); ?>"
                             onerror="this.src='assets/images/default.jpg';">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                            <p class="card-text">₹<?= number_format($product['price'], 2); ?></p>
                            <a href="product_details.php?id=<?= $product['id']; ?>" class="btn btn-primary">View</a>
                            <a href="cart.php?action=add&id=<?= $product['id']; ?>" class="btn btn-success">Add to Cart</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
