<?php
 session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


    $stmt = $conn->prepare("SELECT COUNT(UserID) AS user_count FROM users ");
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

?>
    <!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="style.css">
            <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>
            <title>dashboard</title>
        </head>
        <body>
            <div class="container">
                <div class="topbar">
                    <div class="logo">
                        <a href="index.html"><h2>E-SHOP.</h2></a>
                    </div>
                    
                    <div class="search">
                        <input type="text" id="search" placeholder="search here">
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
                    </a>
                </li>
                <li><a href="#">
                    <i class="fas fa-chart-bar"></i>
                    <div>Sales</div>
                </a>
            </li>
            <li><a href="#">
                <i class="fas fa-hand-holding-usd"></i>
                <div>Earnings</div>
            </a>
        </li>
        <li><a href="index.html">
            <i class="fas fa-th-large"></i>
            <div>Home</div>
        </a>
    </li>
    <li><a href="ContactUs.html">
        <i class="fas fa-question"></i>
        <div>Help</div>
    </a>
    </li>
                    </ul>
                </div>
                <div class="main">
                   <div class="cards">
                        <div class="card">
                            <div class="card-content">
                                <div class="number"><?php if ($count > 0) echo $count; ?></div>
                                <div class="card-name">Customers</div>
                            <div class="icon-box">
                                <i class="fas fa-users"></i>
                            </div>
                            </div>   
                        </div>
                        <div class="card">
                            <div class="card-content">
                                <div class="number">$4500</div>
                                <div class="card-name">Earnings</div>
                            <div class="icon-box">
                                <i class="fas fa-hand-holding-usd"></i>
                            </div>
                            </div>   
                        </div>
                        <div class="card">
                            <div class="card-content">
                                <div class="number">150</div>
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
                    <textarea type="text" id="itemPrice" name="itemDescription" required></textarea>

                    <label for="itemImage">Item ImageURL:</label>
                    <input type="text" id="itemImage" name="itemImage"  required>

                    <label for="itemInventory">Item Inventory:</label>
                    <input type="number" id="itemInventory" name="itemInventory" required>

                    <label for="itemCategory">Item Category:</label>
                    <input type="text" id="itemCategory" name="itemCategory" required>
                    
                    <button type="submit">Upload Item</button>
                </form>
                <form method="post" id="removeForm" action="Remove.php">
                    <h2>Remove Item</h2>
                    <label for="itemName">Item Name:</label>
                    <input type="text" id="itemName" name="itemName" required>
                    
                    <button type="submit">Remove Item</button>
                </form>
            </div>
        </body>
    </html>