<?php
include 'config.php';
include 'accesstoken.php';

// Determine the appropriate IPN registration URL based on the environment
$ipnRegistrationUrl = APP_ENVIROMENT == 'sandbox' 
    ? "https://cybqa.pesapal.com/pesapalv3/api/URLSetup/RegisterIPN" 
    : "https://pay.pesapal.com/v3/api/URLSetup/RegisterIPN";

// Set up the headers for the cURL request
$headers = [
    "Accept: application/json",
    "Content-Type: application/json",
    "Authorization: Bearer $token"
];

// Prepare the data for the IPN registration request
$data = [
    "url" => "https://87ca-102-70-10-227.ngrok-free.app/school-main/group%203/PIN.php",
    "ipn_notification_type" => "POST"
];

// Initialize cURL session
$ch = curl_init($ipnRegistrationUrl);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the cURL request and get the response
$response = curl_exec($ch);
$responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);

curl_close($ch);

if ($error) {
    echo "cURL error: " . $error;
} else {
    echo "HTTP Response Code: " . $responseCode . "<br>";
    echo "Raw Response: " . $response . "<br>";

    // Decode the JSON response
    $data = json_decode($response);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "JSON Decoding Error: " . json_last_error_msg();
    } else {
        // Print entire decoded response for debugging
        echo "Decoded Response: " . print_r($data, true) . "<br>";

        // Check if the IPN ID, URL, and ID are set
        $ipn_id = isset($data->ipn_id) ? $data->ipn_id : 'IPN ID not set';
        $ipn_url = isset($data->url) ? $data->url : 'URL not set';
        $id = isset($data->id) ? $data->id : 'ID not set'; // Handle missing ID

        if ($responseCode == 200) {
            echo "IPN Registered successfully.<br>";
            echo "IPN ID: $ipn_id<br>";
            echo "IPN URL: $ipn_url<br>";
            echo "ID: $id<br>";
        } else {
            echo "Unexpected response from IPN registration: " . json_encode($data);
        }
    }
}
?>
