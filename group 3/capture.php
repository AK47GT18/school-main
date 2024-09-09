<?php
// Load credentials from environment variables
$accessToken = 'YOUR_ACCESS_TOKEN'; // Replace with the actual access token

// Get the JSON data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

// Validate the input data
if (!isset($data['orderID'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input data']);
    exit();
}

// Prepare the PayPal API URL for capturing an order
$apiUrl = "https://api-m.sandbox.paypal.com/v2/checkout/orders/{$data['orderID']}/capture";

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Bearer $accessToken" // Use Bearer token for authorization
]);

// No payload needed for capture request
curl_setopt($ch, CURLOPT_POSTFIELDS, '');

// Execute the cURL request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for cURL errors
if (curl_errno($ch)) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to connect to PayPal']);
    curl_close($ch);
    exit();
}

// Close cURL
curl_close($ch);

// Decode the response from PayPal
$responseData = json_decode($response, true);

// Check if the capture was successful
if ($httpCode === 201 && isset($responseData['id'])) {
    // Return the capture details
    echo json_encode(['status' => 'success', 'captureId' => $responseData['id']]);
} else {
    // Handle errors
    echo json_encode(['status' => 'error', 'message' => 'Failed to capture PayPal order', 'details' => $responseData]);
}
?>
