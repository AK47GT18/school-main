<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the raw POST data sent by the payment gateway
$raw_post_data = file_get_contents('php://input');
$ipn_data = json_decode($raw_post_data, true);

// Log the raw IPN data for debugging
file_put_contents('ipn_log.txt', print_r($ipn_data, true), FILE_APPEND);

// Validate IPN - You can use a token or signature provided by the payment gateway
if (isset($ipn_data['payment_status']) && isset($ipn_data['transaction_id']) && isset($ipn_data['order_id'])) {
    
    // Extract IPN data
    $payment_status = $ipn_data['payment_status'];
    $transaction_id = $ipn_data['transaction_id'];
    $order_id = $ipn_data['order_id']; 
    $amount_paid = $ipn_data['amount'];
    $currency = $ipn_data['currency'];

    // Validate that the order ID exists in your database
    $stmt = $conn->prepare("SELECT TotalPrice FROM orders WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $order = $result->fetch_assoc();

    if (!$order) {
        file_put_contents('ipn_errors.txt', "Order ID not found: $order_id\n", FILE_APPEND);
        http_response_code(400);
        exit();
    }

    // Check if the payment amount and currency match
    if ($amount_paid != $order['TotalPrice'] || $currency != 'MWK') {
        file_put_contents('ipn_errors.txt', "Payment mismatch for Order ID $order_id\n", FILE_APPEND);
        http_response_code(400);
        exit();
    }

    // Handle payment status
    if ($payment_status == "COMPLETED") {
        $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', transaction_id = ? WHERE id = ?");
        $stmt->bind_param("si", $transaction_id, $order_id);
        if ($stmt->execute()) {
            file_put_contents('ipn_log.txt', "Order ID $order_id marked as paid\n", FILE_APPEND);
        } else {
            file_put_contents('ipn_errors.txt', "Failed to update order status for Order ID $order_id\n", FILE_APPEND);
        }
    } else if ($payment_status == "PENDING") {
        file_put_contents('ipn_log.txt', "Payment pending for Order ID $order_id\n", FILE_APPEND);
    } else if ($payment_status == "FAILED") {
        file_put_contents('ipn_errors.txt', "Payment failed for Order ID $order_id\n", FILE_APPEND);
    } else {
        file_put_contents('ipn_errors.txt', "Unknown payment status for Order ID $order_id: $payment_status\n", FILE_APPEND);
    }

    http_response_code(200);
} else {
    file_put_contents('ipn_errors.txt', "Invalid IPN data received\n", FILE_APPEND);
    http_response_code(400);
}

$conn->close();
?>
