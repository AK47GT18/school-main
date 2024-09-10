<?php
// Set the URL for your webhook endpoint
$webhookUrl = "https://87ca-102-70-10-227.ngrok-free.app/school-main/group%203/webhook.php";

// Receive JSON data from the PayChangu webhook
$data = file_get_contents("php://input");
$webhookData = json_decode($data, true);

// Log the webhook data (for debugging)
file_put_contents('webhook.log', print_r($webhookData, true), FILE_APPEND);

// Handle specific events based on webhook data
if (isset($webhookData['status']) && $webhookData['status'] === 'success') {
    // For successful payment
    $paymentId = $webhookData['payment_id'];
    $amount = $webhookData['amount'];
    $userId = $webhookData['user_id'];

    // Process the payment in your database, e.g., update order status
    // Connect to your database
    $conn = new mysqli("localhost", "username", "password", "e-shop");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update order status based on payment success
    $sql = "UPDATE orders SET status='paid' WHERE User_ID='$userId' AND total_amount='$amount'";
    
    if ($conn->query($sql) === TRUE) {
        echo "Order updated successfully";
    } else {
        echo "Error updating order: " . $conn->error;
    }
    
    $conn->close();
    
    // Respond with a 200 OK status
    http_response_code(200);
    echo json_encode(['status' => 'success']);
} else {
    // Handle failure or other statuses
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Payment failed']);
}
?>
