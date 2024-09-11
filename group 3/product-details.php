
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

if (isset($_GET['ProductID'])) {
    $productID = intval($_GET['ProductID']); // Sanitize input

    // Prepare and execute the SQL statement to retrieve product details
    $stmt = $conn->prepare("SELECT product_name, description, price, Image FROM products WHERE product_id = ?");
    $stmt->bind_param("i", $productID); // Bind the ProductID as an integer
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the product exists
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        echo "Product not found.";
        exit;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "No product ID provided.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <title>Product Details</title>
   
</head>
<body>
     <nav class="top-bar">
        
            <ul>
                <li>
                    <p> <B>E-SHOP</B></p>
                
               
                   </li>
                <li>
                    <a href="index.php" class="active"><i><box-icon name='home-alt'></box-icon></i>Home</a>
                </li>
                <li> <a href="shop.php"><box-icon type='solid' name='shopping-bags'></box-icon>Shop</a> </li>
                <li>  <a href="cart.php"> <i><box-icon name='cart' ></box-icon></i>Cart</a> </li>
                <li> <a href="Contact.html"> Contact Us </a></li>
                <?php
                
                if (isset($_SESSION['users_UserID'])) {
                  echo '<li>' . htmlspecialchars($_SESSION['users_FirstName']) . '</li>';
                }
                ?>
            
            
                <div class="srch-bx">
                    <li> <form method="post" action="search-products.php">
                        <input type="search" name="search" placeholder="Search" class="srh">
                        <button type="submit"><box-icon class="sch" name='search'></box-icon></button>
                    </form>
                </li> 
                </div>
            
            <input type="checkbox" id="menu-toggle">
            <label for="menu-toggle" class="ham">
                    <span></span>
                    <span></span>
                    <span></span>
                </label>
               
                    <?php
                
                if (isset($_SESSION['users_UserID'])) {

                 echo ' <nav class="sidebar">        
                    <ul> 
                    <li>My Account</li>
                    <li>
                  <a href="#">About Us</a>
              </li>
              <li>
                  <a href="#">FAQs</a>
              </li>
              <li>
                  <a href="Terms and Conditions.html">Ts and Cs</a>
              </li>
              <li>
                  <a href="Logout.php">Logout</a>

              </li>
                     
                    </ul>
                </nav>';
                }
                else{
                    echo'  
                    <nav class="sidebar">        
                    <ul><li>
                        <a href="Sign-up.html">Sign-Up</a>
                        </li>
                    <li>
                     <a href="Login.html">Login</a>
                     </li>
                     <li>
                     <a href="#">About Us</a>
                     </li>
                     <li>
                     <a href="#">FAQs</a>
                     </li>
                     <li>
                     <a href="Terms and Conditions.html">Ts and Cs</a>
                     </li>
                     </ul>
                    </nav>';
                }
                ?>
                      
                      
               
            </ul>
        </nav>

        <div id="product-details">
        <img id="product-image" src="<?php echo htmlspecialchars($product['Image']); ?>" alt="Product Image">
        <h1 id="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h1>
        <p id="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
        <p id="product-price">Price: <?php echo htmlspecialchars($product['price']); ?></p>
    </div>
   
</body>
</html>
