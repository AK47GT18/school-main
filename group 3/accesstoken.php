<?php
function getPayPalAccessToken() {
    // PayPal credentials
    $clientId = 'ATDmUJc_BrEUewbOg-j-j_oKIkmkYsxsVkM_L-RZirDTPGOt_Mk6op0E5h0NAxiAsPpALG8Fy1r_RB-R'; // Replace with your PayPal Client ID
    $clientSecret = 'EEYMd0Mr9v0ibQP22is_z9AKYTvrxLCRMAswzFjb6PIzLfWjmTsSHOurILGuW2Nc_S4BAaLGczYwPrQ_'; // Replace with your PayPal Client Secret

    // PayPal token endpoint URL
    $tokenUrl = 'https://api-m.sandbox.paypal.com/v1/oauth2/token'; // Use the live URL for production

    // Prepare the cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $tokenUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Accept: application/json',
        'Content-Type: application/x-www-form-urlencoded'
    ]);
    curl_setopt($ch, CURLOPT_USERPWD, "$clientId:$clientSecret");
    curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials');

    // Execute the request
    $response = curl_exec($ch);

    // Check for cURL errors
    if (curl_errno($ch)) {
        curl_close($ch);
        return ['error' => curl_error($ch)];
    }

    // Close cURL
    curl_close($ch);

    // Decode the response
    $responseData = json_decode($response, true);

    // Check if the response contains an access token
    if (isset($responseData['access_token'])) {
        return ['access_token' => $responseData['access_token']];
    } else {
        return ['error' => 'Failed to retrieve access token', 'details' => $responseData];
    }
}
?>
