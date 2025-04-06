<?php
$conn = new mysqli("localhost", "root", "", "madz_thrifts");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle removing an item
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    unset($_SESSION['cart'][$_GET['id']]);
    header("Location: cart.php");
    exit();
}
?>
<?php
session_start();
$conn = new mysqli("localhost", "root", "", "madz_thrifts");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure cart session exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding to cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    // Fetch product details
    $result = $conn->query("SELECT * FROM products WHERE id = '$product_id'");
    $product = $result->fetch_assoc();

    if ($product) {
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'id' => $product['id'], // ✅ Add this line to store product_id
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }else {
            $_SESSION['cart'][$product_id]['quantity'] += 1;
        }
    }
    header("Location: cart.php");
    exit();
}

// Handle removing from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    unset($_SESSION['cart'][$_GET['id']]);
    header("Location: cart.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .cart-container {
            max-width: 900px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .cart-table img {
            width: 70px;
            border-radius: 5px;
        }
        .cart-table th {
            background: #343a40;
            color: #fff;
            text-align: center;
        }
        .cart-table td {
            vertical-align: middle;
            text-align: center;
        }
        .btn-remove {
            background-color: #dc3545;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-remove:hover {
            background-color: #c82333;
        }
        .checkout-btn {
            width: 100%;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="cart-container">
        <h2 class="text-center mb-4">🛒 Your Shopping Cart</h2>

        <?php if (!empty($_SESSION['cart'])): ?>
            <table class="table cart-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach ($_SESSION['cart'] as $id => $item): ?>
                        <tr>
                            <td><img src="<?= htmlspecialchars($item['image']); ?>"></td>
                            <td><?= htmlspecialchars($item['name']); ?></td>
                            <td>₹<?= number_format($item['price'], 2); ?></td>
                            <td><?= $item['quantity']; ?></td>
                            <td>₹<?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <a href="cart.php?action=remove&id=<?= $id; ?>" class="btn-remove">❌ Remove</a>
                            </td>
                        </tr>
                        <?php $total += $item['price'] * $item['quantity']; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <h3 class="text-end mt-3">Total: ₹<?= number_format($total, 2); ?></h3>
            
            <a href="index.php" class="btn btn-primary w-100 mt-3">🛍️ Continue Shopping</a>

            <form action="checkout.php" method="POST">
                <input type="hidden" name="total" value="<?= $total; ?>">
                <button type="submit" class="btn btn-success checkout-btn mt-3">✅ Proceed to Checkout</button>
            </form>

        <?php else: ?>
            <p class="text-center">Your cart is empty. Start shopping now!</p>
            <a href="index.php" class="btn btn-primary w-100">🛍️ Continue Shopping</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
