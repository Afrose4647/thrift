<?php
include '../db.php';
session_start();

// Fetch products
$products = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shop - Madz Thriftz</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<?php include '../includes/navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center">All Products</h2>
    <div class="row">
        <?php while ($product = $products->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="card mb-3">
                    <img src="<?= htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>" onerror="this.onerror=null; this.src='../assets/img/no-image.jpg';">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                        <p class="card-text">₹<?= number_format($product['price'], 2); ?></p>
                        <a href="../product_details.php?id=<?= $product['id']; ?>" class="btn btn-primary">View</a>
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

</body>
</html>
