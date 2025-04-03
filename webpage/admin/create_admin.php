<?php
include '../db.php'; // Ensure this path is correct

$admin_username = "admin";
$admin_password = password_hash("admin123", PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $admin_username, $admin_password);

if ($stmt->execute()) {
    echo "Admin account created successfully!";
} else {
    echo "Error: " . $stmt->error;
}
?>
