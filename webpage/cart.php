<?php
session_start();
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Shopping Cart</h2>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total_price = 0;
                foreach ($_SESSION['cart'] as $id => $product):
                    $total = $product['price'] * $product['quantity'];
                    $total_price += $total;
                ?>
                <tr>
                    <td><img src="<?= $product['image']; ?>" width="50"></td>
                    <td><?= htmlspecialchars($product['name']); ?></td>
                    <td>₹<?= number_format($product['price'], 2); ?></td>
                    <td><?= $product['quantity']; ?></td>
                    <td>₹<?= number_format($total, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <h4>Total: ₹<?= number_format($total_price, 2); ?></h4>
        <a href="checkout.php" class="btn btn-primary">Proceed to Checkout</a>
    <?php else: ?>
        <p>Your cart is empty.</p>
    <?php endif; ?>
</div>

</body>
</html>
