<?php
session_start();
if ( !($_SESSION['role'] === 'Admin')) {
    echo "<script>alert('You aint the admin bruh');</script>";
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
$stmt->close(); 

// Prepare and execute the query to sum the total_price column
$stmt = $conn->prepare("SELECT SUM(TotalPrice) AS total_sum FROM orders");
$stmt->execute();
$stmt->bind_result($totalSum);
$stmt->fetch();
$stmt->close(); 

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
    <script >
function validateUploadForm(event) {
    event.preventDefault();

    const itemName = document.getElementById("itemName").value.trim();
    const itemPrice = document.getElementById("itemPrice").value.trim();
    const itemDescription = document.getElementById("itemDescription").value.trim();
    const itemImage = document.getElementById("itemImage").value.trim();
    const itemInventory = document.getElementById("itemInventory").value.trim();
    const itemCategory = document.getElementById("itemCategory").value.trim();
    const error = document.getElementById("error");

    let valid = true;

    if (!itemName || !itemPrice || !itemDescription || !itemImage || !itemInventory || !itemCategory) {
        error.textContent = "Please fill in all fields.";
        valid = false;
    } else {
        error.textContent = "";
        document.getElementById("uploadForm").submit(); // Submit the form
    }

    return valid;
}

function validateRemoveForm(event) {
    event.preventDefault();

    const itemName = document.getElementById("removeItemName").value.trim();
    const error = document.getElementById("error");

    if (!itemName) {
        error.textContent = "Please enter an item name to remove.";
        return false;
    }

    document.getElementById("removeForm").submit();
}
   </script>
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
                <a href="Contact.php">
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
                <li><a href="Contact.php">
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
                        <div class="number"><?php echo empty($totalSum) ? "0" : "MWK" . $totalSum; ?></div>
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
    <form method="post" action="Upload.php" id="uploadForm" enctype="multipart/form-data" onsubmit="return validateUploadForm(event)" novalidate> 
            <?php
            if (isset($_SESSION['users_UserID'])) {
                echo '<label>Welcome ' . htmlspecialchars($_SESSION['users_FirstName']) . '</label>';
            }
            ?>
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
            <span id="error" style ="color: red;"></span>
        </form>
        <form method="post" action="Remove.php" id="removeForm" onsubmit="return validateRemoveForm(event)" novalidate>            <h2>Remove Item</h2>
            <label for="removeItemName">Item Name:</label>
            <input type="text" id="removeItemName" name="itemName" >
            <span id="error" style ="color: red;"></span>
            <button type="submit" onsubmit="validateForm4()">Remove Item</button>
        </form>
    </div>


</body>
</html>
