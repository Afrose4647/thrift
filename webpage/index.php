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


    <!-- Banner Section -->

    <body>
        <div class="banner-container">
          <div class="banner-slide">
            <img src="assets/ban1.webp" alt="Clothing Banner 1">
          </div>
          <div class="banner-slide">
            <img src="assets/ban2.webp" alt="Clothing Banner 2">
          </div>
          <div class="banner-slide">
            <img src="assets/ban3.webp" alt="Clothing Banner 3">
          </div>
          <!-- View more slides here -->
        </div>
      
        <!-- Navigation buttons -->
        <div class="nav-buttons">
          <button class="prev">&#10094;</button>
          <button class="next">&#10095;</button>
        </div>
      
        <script src="assets/js/ban.js"></script>
      </body>

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
                            <form method="POST" action="cart.php">
    <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
    <button type="submit" class="btn btn-success">Add to Cart</button>
</form>

                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
