<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['users_UserID'])) {
    header("Location: Login.html");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userId = $_SESSION['users_UserID'];

// Validate form submission
if (!isset($_POST['total_price']) || !isset($_POST['products'])) {
    die("Invalid form submission");
}

$totalPrice = $_POST['total_price'];
$products = $_POST['products'];

// Escape user inputs for security
$userId = $conn->real_escape_string($userId);
$totalPrice = $conn->real_escape_string($totalPrice);
$products = $conn->real_escape_string($products);

// Insert order into the database
$stmt = $conn->prepare("INSERT INTO orders (User_ID, TotalPrice, Products, payment_status) VALUES (?, ?, ?, 'Pending')");
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("sss", $userId, $totalPrice, $products);

if ($stmt->execute()) {
    $orderId = $stmt->insert_id;
    $stmt->close();
    
    // Redirect to payment page with the order ID
    header("Location: payments.php?order_id=$orderId");
    exit();
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
