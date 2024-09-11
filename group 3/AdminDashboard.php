<?php
session_start();
if (!($_SESSION['users_UserID'] === '4')) {
    header("Location: Login.html");
    exit();
}
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the query to count users
$stmt = $conn->prepare("SELECT COUNT(UserID) AS user_count FROM users WHERE roles ='customer'");
$stmt->execute();
$stmt->bind_result($count);
$stmt->fetch();
$stmt->close(); // Close the statement

// Prepare and execute the query to sum the total_price column
$stmt = $conn->prepare("SELECT SUM(TotalPrice) AS total_sum FROM orders");
$stmt->execute();
$stmt->bind_result($totalSum);
$stmt->fetch();
$stmt->close(); // Close the statement

$stmt = $conn->prepare("SELECT COUNT(id) AS sales_count FROM orders WHERE payment_status = ?");
$stmt->bind_param("s", $payment_status);
$payment_status = 'completed';
$stmt->execute();
$stmt->bind_result($sales_count);
$stmt->fetch();
$stmt->close();
// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
    <title>Dashboard</title>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div class="logo">
                <a href="index.php"><h2>E-SHOP.</h2></a>
            </div>
            <div class="search">
                <input type="text" id="search" placeholder="Search here">
            </div>
            <a href="cart.php">
                <i class="fa fa-shopping-cart"></i>
            </a>
            <div class="user">
                <a href="ContactUs.html"> 
                    <img src="images/services-icon-png-2309.png" alt="user">
                </a>
            </div>
        </div>
        <div class="sidebar">
            <ul>
                <li><a href="UserManagment.php">
                    <i class="fas fa-users"></i>
                    <div>Customers</div>
                </a></li>
                <li><a href="#">
                    <i class="fas fa-chart-bar"></i>
                    <div>Sales</div>
                </a></li>
                <li><a href="#">
                    <i class="fas fa-hand-holding-usd"></i>
                    <div>Earnings</div>
                </a></li>
                <li><a href="index.php">
                    <i class="fas fa-th-large"></i>
                    <div>Home</div>
                </a></li>
                <li><a href="ContactUs.html">
                    <i class="fas fa-question"></i>
                    <div>Help</div>
                </a></li>
            </ul>
        </div>
        <div class="main">
            <div class="cards">
                <div class="card">
                    <div class="card-content">
                        <div class="number"><?php echo $count; ?></div>
                        <div class="card-name">Customers</div>
                        <div class="icon-box">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>   
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="number"><?php echo "MWK" . number_format($totalSum, 2); ?></div>
                        <div class="card-name">Earnings</div>
                        <div class="icon-box">
                            <i class="fas fa-hand-holding-usd"></i>
                        </div>
                    </div>   
                </div>
                <div class="card">
                    <div class="card-content">
                        <div class="number"><?php echo $sales_count; ?></div>
                        <div class="card-name">Sales</div>
                        <div class="icon-box">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>   
                </div>
            </div>
        </div>
    </div>
    <div class="form">
        <form method="post" action="Upload.php" id="uploadForm" enctype="multipart/form-data">
            <h2>Upload Item</h2>
            <label for="itemName">Item Name:</label>
            <input type="text" id="itemName" name="itemName" required>
            
            <label for="itemPrice">Item Price:</label>
            <input type="number" id="itemPrice" name="itemPrice" required>
            
            <label for="itemDescription">Item Description:</label>
            <textarea id="itemDescription" name="itemDescription" required></textarea>

            <label for="itemImage">Item Image URL:</label>
            <input type="text" id="itemImage" name="itemImage" required>

            <label for="itemInventory">Item Inventory:</label>
            <input type="number" id="itemInventory" name="itemInventory" required>

            <label for="itemCategory">Item Category:</label>
            <input type="text" id="itemCategory" name="itemCategory" required>
            
            <button type="submit">Upload Item</button>
        </form>
        <form method="post" action="Remove.php" id="removeForm">
            <h2>Remove Item</h2>
            <label for="removeItemName">Item Name:</label>
            <input type="text" id="removeItemName" name="itemName" required>
            
            <button type="submit">Remove Item</button>
        </form>
    </div>
</body>
</html>
