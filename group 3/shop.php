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


    $stmt = $conn->prepare("SELECT * FROM products LIMIT 8 ");
    $stmt->execute();
    $result = $stmt->get_result();
?>

<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="author" content="group 3">
        <meta name="Viewpoint" content="width= device-width, initial-scale=0.1">
        <link rel="stylesheet" type="text/css" href="main.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous"/>

        <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
 
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
                <li> <a href="Contact.php"> Contact Us </a></li>
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
   <section class="categories">
    <div class="categories-items">
        <a href="women.php"><p id="categories-description">Clothing For Women</p> </a>
        <img src="images/tamara-bellis-68csPWTnafo-unsplash.jpg" alt="123">
    </div>
    <div class="categories-items">
       <a href="men.php"> <p id="categories-description">Clothing For Men</p></a>
        <img src="images/david-lezcano-NfZiOJzZgcg-unsplash.jpg" alt="123">
    </div>
    <div class="categories-items">
        <a href="shoes.php"><p id="categories-description">Shoes</p></a>
        <img src="images/domino-164_6wVEHfI-unsplash.jpg" alt="123">
    </div>
    <div class="categories-items">
       <a href="Appliances.php"> <p id="categories-description">Electronic Appliances</p></a>
        <img src="images/c-d-x-PDX_a_82obo-unsplash.jpg" alt="123">
    </div> 
   </section>
   <section class="recommend">
    <p>Recommended For You</p>
    <div></div>
    <div></div>
   </section>
</body>
</html>