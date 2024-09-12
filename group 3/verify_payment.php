<?php
// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "e-shop"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve charge_id and orderID from URL parameters, sanitize inputs
$charge_id = isset($_GET['charge_id']) ? filter_var($_GET['charge_id'], FILTER_SANITIZE_SPECIAL_CHARS) : '';
$orderID = isset($_GET['orderID']) ? filter_var($_GET['orderID'], FILTER_SANITIZE_NUMBER_INT) : '';

// Proceed only if both charge_id and orderID are available
if ($charge_id && $orderID) {
    // Initialize cURL session for verification
    $curl = curl_init();

    // Set cURL options
    curl_setopt_array($curl, [
        CURLOPT_URL => "https://api.paychangu.com/mobile-money/payments/$charge_id/verify",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Accept: application/json",
            "Authorization: Bearer sec-live-fs5F40mt4V077CJQfMz4B7Lr2pjU2zF2" // Replace with your actual API key
        ],
    ]);

    // Execute cURL request and get response
    $response = curl_exec($curl);
    $err = curl_error($curl);

    // Close cURL session
    curl_close($curl);

    // Decode JSON response
    $responseData = json_decode($response, true);

    if ($err) {
        $updateStatus = "Error in payment verification: " . $err;
    } else {
        // Check the response status
        $status = $responseData['status'] ?? 'failed'; // Default to failed if no status returned
        
        if ($status === 'success') {
            // Update the order status in the database
            $sql = "UPDATE orders SET payment_status = 'completed' WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $orderID);
            $stmt->execute();

            $updateStatus = "Payment status updated successfully.";
            $stmt->close();
        } else {
            $updateStatus = "Payment verification failed. Status: " . htmlspecialchars($status);
        }
    }
} else {
    $updateStatus = "Invalid charge ID or order ID.";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Verification</title>
    <link rel="stylesheet" href="styles.css"> <!-- Include your CSS file here -->
</head>
<body>
    <div class="response-panel">
        <h1>Payment Verification Result</h1>
        <p>Status: <?php echo htmlspecialchars($status ?? 'N/A'); ?></p>
        <p>Charge ID: <?php echo htmlspecialchars($charge_id); ?></p>
        <p>Order ID: <?php echo htmlspecialchars($orderID); ?></p>
        
        <?php if (isset($err) && $err): ?>
            <p class="error">cURL Error: <?php echo htmlspecialchars($err); ?></p>
        <?php else: ?>
            <p class="success">Verification Response:</p>
            <pre><?php echo htmlspecialchars(print_r($responseData, true)); ?></pre>
        <?php endif; ?>

        <p><?php echo htmlspecialchars($updateStatus); ?></p>
        <a href="index.php">Go back</a>
    </div>
</body>
</html>
