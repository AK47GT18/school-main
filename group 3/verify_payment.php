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

// Retrieve charge_id and orderID from URL parameters
$charge_id = isset($_GET['charge_id']) ? $_GET['charge_id'] : '';
$orderID = isset($_GET['orderID']) ? $_GET['orderID'] : '';

// Define the transaction status (you might need to determine this dynamically)
$status = 'success'; // This should be determined based on actual verification process

// Initialize cURL session
$curl = curl_init();

// Set cURL options
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.paychangu.com/mobile-money/payments/$charge_id/verify?transaction_status=$status",
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



    // Optionally update order status
    $sql = "UPDATE orders SET payment_status = 'completed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $orderID);
    $stmt->execute();
    $updateStatus = "Payment information updated successfully.";

$stmt->close();
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
        <p>Status: <?php echo htmlspecialchars($status); ?></p>
        <p>Charge ID: <?php echo htmlspecialchars($charge_id); ?></p>
        <p>Order ID: <?php echo htmlspecialchars($orderID); ?></p>
        
        <?php if ($err): ?>
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
