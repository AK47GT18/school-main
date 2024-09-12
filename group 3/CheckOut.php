<?php
session_start(); 

// Redirect if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.php");
    exit();
}

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

// Get the logged-in user's details from the session
$userID = $_SESSION['users_UserID']; 
$userEmail = $_SESSION['user_email']; 
$firstName = $_SESSION['users_FirstName']; 
$Phone = $_SESSION['PhoneNumber']; 
$totalPrice = isset($_POST['total_price']) ? (float)$_POST['total_price'] : 0;
$products = isset($_POST['products']) ? $_POST['products'] : '';

if ($totalPrice <= 0) {
    echo "Invalid total price.";
    exit();
}

// Generate a random charge_id
$charge_id = bin2hex(random_bytes(16));

// Insert order into the database
$stmt = $conn->prepare("INSERT INTO orders (User_ID, TotalPrice, Products, ChargeID) VALUES (?, ?, ?, ?)");
$stmt->bind_param("iiss", $userID, $totalPrice, $products, $charge_id);

if ($stmt->execute()) {
    echo "Order placed successfully with amount: $totalPrice";

    // Retrieve the most recent order ID for the user
    $sql = "SELECT id FROM orders WHERE User_ID = ? ORDER BY id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("i", $userID);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $orderID = $row['id'];

            $mobileMoneyOperatorRefId = '27494cb5-ba9e-437f-a114-4e7a7686bcca';
            if ($mobileMoneyOperatorRefId !== null && !empty($totalPrice)) {
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
                        'mobile' => $Phone,
                        'mobile_money_operator_ref_id' => $mobileMoneyOperatorRefId,
                        'amount' => $totalPrice,
                        'charge_id' => $charge_id,
                        'email' => $userEmail,
                        'first_name' => $firstName,
                    ]),
                    CURLOPT_HTTPHEADER => [
                        "Authorization: Bearer sec-live-fs5F40mt4V077CJQfMz4B7Lr2pjU2zF2",
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
                    header("Location: verify_payment.php?charge_id=$charge_id&orderID=$orderID");
                    exit();
                }
            } else {
                echo "Invalid mobile number or no valid amount found.";
            }
        } else {
            echo "No orders found for the specified user.";
        }
        $stmt->close();
    } else {
        echo "Error in SQL statement: " . $conn->error;
    }
} else {
    echo "Error inserting order: " . $stmt->error;
    exit();
}

$conn->close();
?>
