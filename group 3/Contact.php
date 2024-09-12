
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
        <section class="contact">
            <form action="ContactUs.php" method="post">
            <div class="message">
                <input type="email" placeholder="Email" name="email">
                <input type="text" placeholder="Name" name="UserName">
                <input type="text" placeholder="Message" name="message">
                <button>Send Message</button>
            </div>
        </form>
            <div class="News">
                <h6>Our Newsletters</h6>
                <p>hpiodfjapoefjapohfaihfpiahfdowadp9ahsfi9a<br>jposfhaspiofhpioajfdpoawjfpiashiofhasiofais</p>
                <input type="text" placeholder="Email">
                <button>Submit</button>
            </div>
        </section>
        <section class="contact-cards">
            <div><box-icon name='envelope' type='solid' ></box-icon><h6> cen01-01-22@unilia.ac.mw</h6> 
                  <p>hpiodfjapoefjapohfaihfpiahfdowadp9ahsfi9a<br>jposfhaspiofhpioajfdpoawjfpiashiofhasiofais</p></div>
            <div><box-icon name='phone'></box-icon><h6> (+265)885620896 </h6> 
                  <p>hpiodfjapoefjapohfaihfpiahfdowadp9ahsfi9a<br>jposfhaspiofhpioajfdpoawjfpiashiofhasiofais</p></div>
            <div><box-icon name='map'></box-icon><h6>Northen-Region, Rumphi, Malawi </h6> 
                  <p>hpiodfjapoefjapohfaihfpiahfdowadp9ahsfi9a<br>jposfhaspiofhpioajfdpoawjfpiashiofhasiofais</p></div>
        </section>
        <div class="map"><iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3921.562152234342!2d34.104948073841925!3d-10.613365416868602!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19025ef8944cb157%3A0x5aea743d536d4c21!2sLivingstonia%20University%2C%20Malawi!5e0!3m2!1sen!2smw!4v1724735519828!5m2!1sen!2smw" width="1000" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
        <section>
            
        </section>
    </body>
</html>