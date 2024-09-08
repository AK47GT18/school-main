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

// Function to get user data from the database
// Function to get user data from the database
function getUserData($conn, $user_id) {
    $stmt = $conn->prepare("SELECT Email, PhoneNumber, FirstName, LastName FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc() ?: null;
}

// Function to get order data from the database
function getOrderData($conn, $order_id) {
    $stmt = $conn->prepare("SELECT oi.order_id, oi.product_id, oi.price, u.Email, u.PhoneNumber, u.FirstName, u.LastName
                             FROM order_items oi
                             JOIN orders o ON oi.order_id = o.id
                             JOIN users u ON o.User_ID = u.UserID
                             WHERE oi.order_id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $order_data = [];
    while ($row = $result->fetch_assoc()) {
        $order_data[] = $row;
    }
    return $order_data;
}


// Function to submit an order
function submitOrder($order_data, $token) {
    $url = "https://cybqa.pesapal.com/pesapalv3/api/Transactions/SubmitOrderRequest"; // Replace with your API endpoint

    $data = json_encode($order_data);

    $ch = curl_init($url);
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

// Function to retrieve the access token
function getAccessToken() {
    define('APP_ENVIRONMENT', 'sandbox'); // Change to 'live' for production

    $apiUrl = APP_ENVIRONMENT === 'sandbox' 
        ? "https://cybqa.pesapal.com/pesapalv3/api/Auth/RequestToken" 
        : "https://pay.pesapal.com/v3/api/Auth/RequestToken";

    $consumer_key = "htMsEFfIVHfhqBL9O0ykz8wuedfFyg1s";
    $consumer_secret = "DcwkVNIiyijNWn1fdL/pa4K6khc=";

    $headers = [
        "Accept: application/json",
        "Content-Type: application/json"
    ];

    $data = [
        "consumer_key" => $consumer_key,
        "consumer_secret" => $consumer_secret
    ];

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    if ($error) {
        return null;
    }

    $data = json_decode($response, true);
    return $data['token'] ?? null;
}

// Function to register IPN
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

// Main Execution
$token = getAccessToken();

if ($token) {
    $order_id = 12345; // Replace with the actual order ID
    $order_data = getOrderData($conn, $order_id);

    if ($order_data) {
        $billing_address = [
            "email_address" => $order_data[0]['email'],
            "phone_number" => $order_data[0]['phone_number'],
            "country_code" => "MW",
            "first_name" => $order_data[0]['FirstName'],
            "last_name" => $order_data[0]['LastName'],
            "city" => "Lilongwe",
            "state" => "",
            "postal_code" => "00000"
        ];

        $order_items = array_map(function ($item) {
            return [
                "product_id" => $item['product_id'],
                "price" => $item['price']
            ];
        }, $order_data);

        $order_request_data = [
            "id" => $order_id,
            "currency" => "MWK",
            "amount" => array_sum(array_column($order_items, 'price')), // Total price
            "description" => "Order ID: $order_id",
            "callback_url" => "https://yourdomain.com/callback",
            "notification_id" => "notification_id_here",
            "billing_address" => $billing_address,
            "order_items" => $order_items // Include order items
        ];

        $order_response = submitOrder($order_request_data, $token);
        print_r($order_response);

        $ipn_url = "https://localhost/school-main/group%203/PIN.php/pespal/ipn";
        $notification_type = "POST";

        $ipn_response = registerIpn($ipn_url, $notification_type, $token);
        
        // Display the IPN ID
        if (isset($ipn_response['ipn_id'])) {
            echo "IPN ID: " . $ipn_response['ipn_id'];
        } else {
            echo "IPN ID not found in the response.";
        }
    } else {
        echo "Order data not found.";
    }
} else {
    echo "Failed to retrieve token.";
}

$conn->close();
?>
