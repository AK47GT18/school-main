<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['user_id'])) {
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

// Retrieve the order ID from the query parameters
$orderId = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($orderId <= 0) {
    die("Invalid order ID");
}

// Fetch order details
$stmt = $conn->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->bind_param("i", $orderId);
$stmt->execute();
$orderResult = $stmt->get_result();

if ($orderResult->num_rows === 0) {
    die("Order not found");
}

$order = $orderResult->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Order Confirmation</title>
    <!-- Boxicons CSS for icons -->
    <link href='https://unpkg.com/boxicons/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <nav class="top-bar">
        <ul>
            <li><p><b>E-SHOP</b></p></li>
            <li><a href="index.php" class="active"><i><box-icon name='home-alt'></box-icon></i>Home</a></li>
            <li><a href="shop.html"><box-icon type='solid' name='shopping-bags'></box-icon>Shop</a></li>
            <li><a href="cart.html"><i><box-icon name='cart'></box-icon></i>Cart</a></li>
            <li><a href="ContactUs.html">Contact Us</a></li>
            <div class="srch-bx">
                <li>
                    <input type="search" placeholder="search" class="srh">
                    <i><box-icon class="sch" name='search'></box-icon></i>
                </li>
            </div>
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="ham">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <nav class="sidebar">
                <ul>
                    <li><a href="Sign-up.html">Sign-Up</a></li>
                    <li><a href="Login.html">Login</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Ts and Cs</a></li>
                </ul>
            </nav>
        </ul>
    </nav>
    <section class="confirmation">
        <h1>Order Confirmation</h1>
        <p>Thank you for your order!</p>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order['id']); ?></p>
        <p><strong>Total Price:</strong> <?php echo htmlspecialchars($order['TotalPrice']); ?></p>
        <p><strong>Products:</strong> <?php echo htmlspecialchars($order['Products']); ?></p>
        <p><strong>Currency:</strong> <?php echo htmlspecialchars($order['currency']); ?></p>
        <p><strong>Payment Status:</strong> <?php echo htmlspecialchars($order['payment_status']); ?></p>
        <?php if (!empty($order['transaction_id'])): ?>
            <p><strong>Transaction ID:</strong> <?php echo htmlspecialchars($order['transaction_id']); ?></p>
        <?php endif; ?>
    </section>
</body>
</html>
