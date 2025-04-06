<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "madz_thrifts");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<script>alert('Your cart is empty!'); window.location.href='cart.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$key_id = "rzp_test_hx0iKfup5dpdhj";
$total_price = 0;
foreach ($_SESSION['cart'] as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
$amount_in_paise = $total_price * 100;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout - Madz Thriftz</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap + Razorpay -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <!-- Custom Styling -->
    <style>
        body {
            background: #f9f9f9;
        }
        .checkout-card {
            max-width: 600px;
            margin: auto;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.08);
            background: #fff;
            border-radius: 10px;
        }
        h2 {
            font-weight: bold;
        }
        label {
            font-weight: 500;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #198754;
        }
        .total-price {
            font-size: 1.4rem;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center mb-4 text-success">Checkout</h2>
    <div class="card checkout-card p-4">
        <form id="checkoutForm">
            <div class="mb-3">
                <label for="fullname">Full Name</label>
                <input type="text" name="fullname" class="form-control" id="fullname" required>
            </div>
            <div class="mb-3">
                <label for="address">Shipping Address</label>
                <textarea name="address" class="form-control" id="address" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="phone">Phone Number</label>
                <input type="text" name="phone" class="form-control" id="phone" pattern="\d{10}" title="Enter 10-digit phone number" required>
            </div>
            <div class="mb-3">
                <label>Payment Method</label><br>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_method" value="cod" checked>
                    <label class="form-check-label">Cash on Delivery</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="payment_method" value="online">
                    <label class="form-check-label">Online Payment</label>
                </div>
            </div>

            <input type="hidden" name="total" value="<?= $total_price ?>">
            <div class="total-price text-center text-dark">Total: ₹<?= number_format($total_price, 2); ?></div>

            <button type="submit" class="btn btn-outline-primary w-100 mt-4" id="codBtn">Place COD Order</button>
            <button type="button" class="btn btn-success w-100 mt-4 d-none" id="payBtn">Pay with Razorpay</button>
        </form>
    </div>
</div>

<script>
const codBtn = document.getElementById('codBtn');
const payBtn = document.getElementById('payBtn');
const form = document.getElementById('checkoutForm');

// Toggle button visibility
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', () => {
        codBtn.classList.toggle('d-none', radio.value !== 'cod');
        payBtn.classList.toggle('d-none', radio.value !== 'online');
    });
});

// COD Submission
codBtn.onclick = function(e) {
    e.preventDefault();
    const formData = new FormData(form);
    fetch('cod_success.php', {
        method: 'POST',
        body: formData
    }).then(res => res.text())
      .then(url => window.location.href = url);
};

// Razorpay Integration
payBtn.onclick = function(e) {
    e.preventDefault();
    const formData = new FormData(form);
    const options = {
        "key": "<?= $key_id ?>",
        "amount": "<?= $amount_in_paise ?>",
        "currency": "INR",
        "name": "Madz Thriftz",
        "description": "Product Order",
        "handler": function (response) {
            fetch('razorpay_success.php', {
                method: 'POST',
                body: new URLSearchParams({
                    razorpay_payment_id: response.razorpay_payment_id,
                    fullname: formData.get("fullname"),
                    address: formData.get("address"),
                    phone: formData.get("phone"),
                    total: formData.get("total")
                })
            }).then(res => res.text())
              .then(url => window.location.href = url);
        },
        "prefill": {
            "name": formData.get("fullname"),
            "email": "<?= $_SESSION['user_email'] ?? 'guest@madz.com' ?>"
        },
        "theme": {
            "color": "#198754"
        }
    };
    const rzp = new Razorpay(options);
    rzp.open();
};
</script>
</body>
</html>
