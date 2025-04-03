<?php
session_start();
include '../db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];
$total_price = 0;

foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Checkout - Madz Thriftz</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <h2>Checkout</h2>
    <p>Total Amount: ₹<?= $total_price; ?></p>
    <button id="pay-btn">Pay with Razorpay</button>

    <script>
        $("#pay-btn").click(function () {
            $.post("razorpay_order.php", function (response) {
                let data = JSON.parse(response);
                if (data.status === "success") {
                    let options = {
                        key: "YOUR_RAZORPAY_KEY_ID",
                        amount: data.amount * 100,
                        currency: "INR",
                        name: "Madz Thriftz",
                        description: "Clothing Purchase",
                        order_id: data.order_id,
                        handler: function (payment) {
                            $.post("verify_payment.php", { payment_id: payment.razorpay_payment_id, order_id: data.order_id }, function (res) {
                                let result = JSON.parse(res);
                                if (result.status === "success") {
                                    window.location.href = "../orders/index.php";
                                } else {
                                    alert("Payment failed!");
                                }
                            });
                        }
                    };
                    let rzp = new Razorpay(options);
                    rzp.open();
                } else {
                    alert("Payment initiation failed!");
                }
            });
        });
    </script>

</body>
</html>
