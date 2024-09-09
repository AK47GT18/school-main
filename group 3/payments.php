<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.html");
    exit();
}

// Retrieve order_id from query parameters
$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch order details from the database
$stmt = $conn->prepare("SELECT TotalPrice FROM orders WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$totalPrice = $order['TotalPrice'];
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment</title>
</head>
<body>
<script src="https://www.paypal.com/sdk/js?client-id=ATDmUJc_BrEUewbOg-j-j_oKIkmkYsxsVkM_L-RZirDTPGOt_Mk6op0E5h0NAxiAsPpALG8Fy1r_RB-R&currency=USD"></script>

<div id="paypal-button-container"></div>

<script>
paypal.Buttons({
    createOrder: function(data, actions) {
        return actions.order.create({
            purchase_units: [{
                amount: {
                    value: '<?php echo $totalPrice; ?>' // Dynamically pass the PHP total price
                }
            }]
        });
    },
    onApprove: function(data, actions) {
        return actions.order.capture().then(function(details) {
            console.log('Capture details:', details); // Log the full response

            return fetch('capture.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    orderID: data.orderID, // Ensure correct field name
                    orderId: <?php echo json_encode($order_id); ?>,
                    totalPrice: <?php echo json_encode($totalPrice); ?>
                })
            }).then(function(res) {
                return res.json();
            }).then(function(response) {
                console.log('Server response:', response); // Log the response from the server
                
                if (response.status === 'success') {
                    alert('Transaction completed by ' + (response.payerName || 'Anonymous'));
                    window.location.href = 'confirmation.php?order_id=' + <?php echo json_encode($order_id); ?>;
                } else {
                    alert('Transaction completed, but there was an issue with processing the payment.');
                }
            }).catch(function(error) {
                console.error('Error capturing order:', error);
                alert('An error occurred while processing your payment.');
            });
        });
    }
}).render('#paypal-button-container');
</script>
</body>
</html>
