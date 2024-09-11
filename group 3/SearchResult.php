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

if (isset($_GET['query'])) {
    $Search = $_GET['query'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_name LIKE ?");
    $like = "%" . $Search . "%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $result = $stmt->get_result();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="group 3">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
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
    <?php
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $imageData = $row['Image']; 
            $productName = htmlspecialchars($row['product_name'] ?? '');
            $price = htmlspecialchars($row['price'] ?? '');
           
          
            echo '<div class="items">
                    <img class="ProductImg" src='.$imageData.' alt="' . $productName . '">
                    <p class="description">' . $productName . '</p>
                    <p class="Price-description">Price: <span class="Price">MWK ' . $price . '</span></p>
                    <label for="quantity" id="quantity-label">Quantity</label>
                    <input id="quantity" type="number">
                    <input class="btn3" type="button" value="Add To Cart">
                 </div>';
        }
    } else {
        echo "<script>alert('No data found in the database');</script>";
    }
    $conn->close();
    ?>
    <script> document.addEventListener("DOMContentLoaded", () => {
   const products = document.querySelectorAll(".items");
   products.forEach(product => { 
      product.addEventListener("dblclick", function() {
        const productId = product.dataset.id;
      
        window.location.href = "product-details.php?ProductID=" + productId;
      });

      // Add to cart functionality
      product.querySelector(".btn3").addEventListener("click", (event) => {
         const productID = product.getAttribute('data-id');
         const name = product.querySelector(".description").textContent;
         const price = product.querySelector(".Price").textContent;
         const image = product.querySelector(".ProductImg").src;
     
         let productDetails = JSON.parse(localStorage.getItem('SelectedProducts')) || [];
         const Details = { ID: productID, Image: image, Name: name, Price: price };
     
         const existingIndex = productDetails.findIndex(item => item.ID === productID);
         if (existingIndex !== -1) {
            productDetails[existingIndex] = Details;
         } else {
            productDetails.push(Details);
         }
     
         localStorage.setItem('SelectedProducts', JSON.stringify(productDetails));
      });
   });
});
</script>
</body>
</html>
