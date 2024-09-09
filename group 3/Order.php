<?php
// Load credentials from environment variables (ensure these are set in your environment)
$clientId = 'ATDmUJc_BrEUewbOg-j-j_oKIkmkYsxsVkM_L-RZirDTPGOt_Mk6op0E5h0NAxiAsPpALG8Fy1r_RB-R'; // Replace with your PayPal Client ID
$clientSecret = 'EEYMd0Mr9v0ibQP22is_z9AKYTvrxLCRMAswzFjb6PIzLfWjmTsSHOurILGuW2Nc_S4BAaLGczYwPrQ_'; // Replace with your PayPal Client Secret

// Check if environment variables are set
if (!$clientId || !$clientSecret) {
    echo json_encode(['status' => 'error', 'message' => 'Missing PayPal credentials']);
    exit;
}

// Get the JSON data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

// Validate input data
if (!isset($data['totalPrice'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing order details']);
    exit;
}

$totalPrice = $data['totalPrice'];

// Prepare the PayPal API URL for creating an order (Sandbox URL for testing)
$apiUrl = "https://api-m.sandbox.paypal.com/v2/checkout/orders";

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "Authorization: Basic " . base64_encode("$clientId:$clientSecret")
]);

// Prepare the PayPal order data
$payPalOrderData = [
    "intent" => "CAPTURE",
    "purchase_units" => [
        [
            "amount" => [
                "currency_code" => "USD",
                "value" => $totalPrice
            ]
        ]
    ]
];

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payPalOrderData));

// Execute the cURL request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for cURL errors
if (curl_errno($ch)) {
    error_log('cURL Error: ' . curl_error($ch)); // Log the error
    echo json_encode(['status' => 'error', 'message' => 'Failed to connect to PayPal']);
    curl_close($ch);
    exit;
}

// Close cURL
curl_close($ch);

// Decode the response from PayPal
$responseData = json_decode($response, true);

// Check if the order creation was successful
if ($httpCode === 201 && isset($responseData['id'])) {
    // Return the PayPal order ID
    echo json_encode(['status' => 'success', 'id' => $responseData['id']]);
} else {
    // Handle errors
    error_log('PayPal Response Error: ' . $response); // Log the response
    echo json_encode(['status' => 'error', 'message' => 'Failed to create PayPal order', 'details' => $responseData]);
}
?>
