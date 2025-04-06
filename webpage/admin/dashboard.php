<?php
session_start();
include '../db.php';

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}

// Get stats
$total_products = $conn->query("SELECT COUNT(*) as total FROM products")->fetch_assoc()['total'];
$total_orders = $conn->query("SELECT COUNT(*) as total FROM orders")->fetch_assoc()['total'];
$pending_orders = $conn->query("SELECT COUNT(*) as total FROM orders WHERE status = 'Pending'")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Madz Thriftz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .dashboard-box {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.05);
        }
        .dashboard-title {
            font-size: 26px;
            font-weight: bold;
        }
        .stat-card {
            background-color: #fff;
            border-left: 5px solid #198754;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .stat-card h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 500;
        }
        .stat-card p {
            font-size: 28px;
            font-weight: bold;
            color: #198754;
        }
        .nav-links a {
            text-decoration: none;
            margin: 10px 15px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container mt-5 dashboard-box">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="dashboard-title">👨‍💼 Admin Dashboard</div>
            <div><a href="logout.php" class="btn btn-danger">Logout</a></div>
        </div>

        <div class="nav-links mb-4">
            <a href="add_product.php" class="btn btn-outline-primary">➕ Add Product</a>
            <a href="manage_orders.php" class="btn btn-outline-success">📦 Manage Orders</a>
        </div>

        <div class="row text-center">
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <h4>Total Products</h4>
                    <p><?= $total_products ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <h4>Total Orders</h4>
                    <p><?= $total_orders ?></p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="stat-card">
                    <h4>Pending Orders</h4>
                    <p><?= $pending_orders ?></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
