<?php
session_start();
require '../db.php';
require '../vendor/autoload.php';

use Razorpay\Api\Api;

$api_key = "rzp_test_hx0iKfup5dpdhj";
$api_secret = "xxxxxxxxxxxxxxxxxxxxxxxx";
$api = new Api($api_key, $api_secret);

if (!isset($_SESSION['user_id'])) {
    die(json_encode(["status" => "error", "message" => "Login required!"]));
}

$user_id = $_SESSION['user_id'];
$cart = $_SESSION['cart'] ?? [];
$total_price = 0;

foreach ($cart as $item) {
    $total_price += $item['price'] * $item['quantity'];
}

$orderData = [
    'receipt' => 'order_' . uniqid(),
    'amount' => $total_price * 100, // Amount in paise
    'currency' => 'INR',
    'payment_capture' => 1
];

$razorpayOrder = $api->order->create($orderData);

$_SESSION['razorpay_order_id'] = $razorpayOrder['id'];

echo json_encode([
    "status" => "success",
    "order_id" => $razorpayOrder['id'],
    "amount" => $total_price
]);
?>
