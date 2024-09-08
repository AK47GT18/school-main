<?php

include 'accesstoken.php';
function registerIpn($url, $ipn_notification_type, $token) {
    $apiUrl = "https://cybqa.pesapal.com/pesapalv3/api/URLSetup/RegisterIPN";

    $data = json_encode([
        "url" => $url,
        "ipn_notification_type" => $ipn_notification_type
    ]);

    $ch = curl_init($apiUrl);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $token
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return ['error' => $error];
    }

    return json_decode($response, true);
}

$consumer_key = "htMsEFfIVHfhqBL9O0ykz8wuedfFyg1s";
$consumer_secret = "DcwkVNIiyijNWn1fdL/pa4K6khc=";

if ($token_response && isset($token_response['token'])) {
    $token = $token_response['token'];
    echo "Token: " . $token . "<br>";

    // Register IPN
    $ipn_url = "https://localhost/school-main/group%203/PIN.php/pespal/ipn";
    $notification_type = "POST";

    $ipn_response = registerIpn($ipn_url, $notification_type, $token);
    //Display the IPN ID
    if (isset($ipn_response['ipn_id'])) {
        echo "IPN ID: " . $ipn_response['ipn_id'];
    } else {
        echo "IPN ID not found in the response.";
    }
} else {
    echo "Failed to retrieve token.";
}
?>
