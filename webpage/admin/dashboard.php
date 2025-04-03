<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Madz Thriftz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand">Admin Dashboard</span>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Welcome, <?= $_SESSION['admin_username']; ?></h2>
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="card text-bg-primary p-3">
                    <h4>Total Products</h4>
                    <p>100+</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-success p-3">
                    <h4>Total Orders</h4>
                    <p>50+</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-bg-warning p-3">
                    <h4>Total Users</h4>
                    <p>200+</p>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
