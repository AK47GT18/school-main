<?php
session_start(); // Start the session to use session variables

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

// Check if the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    die("User not logged in.");
}

// Get the logged-in user's ID from the session
$userID = $_SESSION['users_UserID']; // UserID stored during login
$userEmail = $_SESSION['user_email']; // Email stored during login
$firstName = $_SESSION['users_FirstName']; // FirstName stored during login

// Query to retrieve user and order data from the database
$sql = "SELECT u.PhoneNumber, o.TotalPrice 
        FROM users u 
        INNER JOIN orders o ON u.UserID = o.User_ID 
        WHERE u.UserID = ? AND o.payment_status = 'pending'";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("i", $userID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch the row
        $row = $result->fetch_assoc();
        $mobile = $row['PhoneNumber'];
        $amount = $row['TotalPrice']; // Retrieve total amount from the orders table

        // Check if the number starts with '08' or '09'
        if (strpos($mobile, '08') === 0) {
            $mobileMoneyOperatorRefId = '27494cb5-ba9e-437f-a114-4e7a7686bcca';
        } elseif (strpos($mobile, '09') === 0) {
            $mobileMoneyOperatorRefId = '20be6c20-adeb-4b5b-a7ba-0769820df4fb';
        } else {
            $mobileMoneyOperatorRefId = null;
        }

        // Ensure the mobile_money_operator_ref_id is valid before proceeding with the cURL request
        if ($mobileMoneyOperatorRefId !== null && !empty($amount)) {
            // cURL request (POST)
            $curl = curl_init();

            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.paychangu.com/mobile-money/payments/initialize",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => json_encode([
                    'mobile' => $mobile,
                    'mobile_money_operator_ref_id' => $mobileMoneyOperatorRefId,
                    'amount' => $amount, // Dynamic amount from the orders table
                    'charge_id' => '190', // Example charge ID
                    'email' => $userEmail, // Email from session
                    'first_name' => $firstName, // First name from session
                    'last_name' => $_SESSION['users_LastName'] // Last name from session (add to login session if needed)
                ]),
                CURLOPT_HTTPHEADER => [
                    "Authorization: Bearer sec-test-hGZO2qC50metjPeq4SSJhn4iXMDPNcID",
                    "accept: application/json",
                    "content-type: application/json"
                ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);

            curl_close($curl);

            if ($err) {
                echo "cURL Error #2: " . $err;
            } else {
                echo "Second Request Response: " . $response;
            }
        } else {
            echo "Invalid mobile number or no valid amount found.";
        }

    } else {
        echo "No data found for the specified user or no pending orders.";
    }
    $stmt->close();
} else {
    echo "Error in SQL statement: " . $conn->error;
}

$conn->close();
?>
