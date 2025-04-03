<?php
$host = "localhost";
$user = "root"; // Change if you have a different username
$pass = ""; // Change if you set a password
$dbname = "madz_thrifts";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
?>
