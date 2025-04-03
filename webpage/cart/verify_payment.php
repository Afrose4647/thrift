<?php
session_start();
require '../db.php';
require '../vendor/autoload.php';
require '../admin/send_email.php';

use Razorpay\Api\Api;

$api_key = "YOUR_RAZORPAY_KEY_ID";
$api_secret = "YOUR_RAZORPAY_KEY_SECRET";
$api = new Api($api_key, $api_secret);

if (!isset($_POST['payment_id']) || !isset($_POST['order_id'])) {
    die(json_encode(["status" => "error", "message" => "Invalid request!"]));
}

$payment_id = $_POST['payment_id'];
$order_id = $_POST['order_id'];
$user_id = $_SESSION['user_id'];

$user = $conn->query("SELECT email FROM users WHERE id = $user_id")->fetch_assoc();
$user_email = $user['email'];

try {
    $payment = $api->payment->fetch($payment_id);

    if ($payment->status == "captured") {
        $cart = $_SESSION['cart'] ?? [];
        $total_price = 0;

        foreach ($cart as $item) {
            $total_price += $item['price'] * $item['quantity'];
        }

        // Store order in database
        $conn->begin_transaction();
        $stmt = $conn->prepare("INSERT INTO orders (user_id, total_price, status) VALUES (?, ?, 'Pending')");
        $stmt->bind_param("id", $user_id, $total_price);
        $stmt->execute();
        $order_id_db = $stmt->insert_id;
        $stmt->close();

        foreach ($cart as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id_db, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }
        $stmt->close();

        $conn->commit();
        unset($_SESSION['cart']);

        // Send Order Confirmation Email
        $subject = "Order Confirmation - Madz Thriftz";
        $body = "<h2>Thank you for your order!</h2>
                 <p>Your order ID is <b>$order_id_db</b>.</p>
                 <p>We will notify you once your order is shipped.</p>";

        sendEmail($user_email, $subject, $body);

        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Payment not captured!"]);
    }
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Payment verification failed!"]);
}
?>
