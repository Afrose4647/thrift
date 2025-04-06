<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Quick stats
$total_orders = $conn->query("SELECT COUNT(*) AS total FROM orders")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$total_revenue = $conn->query("SELECT SUM(total_price) AS revenue FROM orders WHERE status='Delivered'")->fetch_assoc()['revenue'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Madz Thriftz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background-color: #f8f9fa;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 60px;
        }

        .sidebar h3 {
            text-align: center;
            margin-bottom: 30px;
        }

        .sidebar a {
            display: block;
            padding: 15px 25px;
            color: white;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
        }

        .content {
            margin-left: 240px;
            padding: 30px;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
        }

        .card h4 {
            font-size: 20px;
        }

        .logout-link {
            position: absolute;
            bottom: 20px;
            width: 100%;
        }

        .logout-link a {
            color: red;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>Madz Thriftz 👑</h3>
    <a href="dashboard.php">📊 Dashboard</a>
    <a href="manage_products.php">🛍️ Manage Products</a>
    <a href="manage_orders.php">📦 Manage Orders</a>
    <div class="logout-link">
        <a href="logout.php">🚪 Logout</a>
    </div>
</div>

<div class="content">
    <h2 class="mb-4">📈 Dashboard Overview</h2>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-bg-primary p-3">
                <h4>Total Orders</h4>
                <p class="fs-4"><?= $total_orders; ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success p-3">
                <h4>Total Revenue</h4>
                <p class="fs-4">₹<?= number_format($total_revenue ?? 0, 2); ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-warning p-3">
                <h4>Total Products</h4>
                <p class="fs-4"><?= $total_products; ?></p>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info p-3">
                <h4>Registered Users</h4>
                <p class="fs-4"><?= $total_users; ?></p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
