<?php
session_start();
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit();
}
include '../db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $description = $_POST['description']; // New description field

    // ImgBB API Key
    $imgbb_api_key = "3b7a4c1077c49c890c0e45249a6c870d";  // Replace with your ImgBB API key

    // Handle image upload to ImgBB
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_temp = $_FILES['image']['tmp_name'];
        $image_name = $_FILES['image']['name'];

        // Convert image to base64
        $image_data = base64_encode(file_get_contents($image_temp));

        // Upload to ImgBB
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.imgbb.com/1/upload?key=$imgbb_api_key");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'image' => $image_data
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $response_data = json_decode($response, true);
        if (isset($response_data['data']['url'])) {
            $image_url = $response_data['data']['url']; // Get the ImgBB image URL

            // Insert product into MySQL database
            $stmt = $conn->prepare("INSERT INTO products (name, price, description, image) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sdss", $name, $price, $description, $image_url);
            if ($stmt->execute()) {
                echo "<script>alert('Product added successfully!');</script>";
            } else {
                echo "<script>alert('Error adding product.');</script>";
            }
        } else {
            echo "<script>alert('Image upload failed!');</script>";
        }
    } else {
        echo "<script>alert('Please select an image.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product - Madz Thriftz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <h2 class="text-center">Add New Product</h2>
        <div class="card p-4 shadow">
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Product Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Price ($)</label>
                    <input type="number" name="price" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Upload Image</label>
                    <input type="file" name="image" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Add Product</button>
            </form>
        </div>
        <a href="manage_products.php" class="btn btn-secondary mt-3">Back to Manage Products</a>
    </div>
</body>
</html>
