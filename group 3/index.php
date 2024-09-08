
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


    $stmt = $conn->prepare("SELECT * FROM products  ");
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
        <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
 
     </head>
      
    <body>
        <nav class="top-bar">
        
            <ul>
                <li>
                    <p> <B>E-SHOP</B></p>
                
               
                   </li>
                <li>
                    <a href="index.html" class="active"><i><box-icon name='home-alt'></box-icon></i>Home</a>
                </li>
                <li> <a href="shop.html"><box-icon type='solid' name='shopping-bags'></box-icon>Shop</a> </li>
                <li>  <a href="cart.php"> <i><box-icon name='cart' ></box-icon></i>Cart</a> </li>
                <li> <a href="ContactUs.html"> Contact Us </a></li>
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
                    <li>' . htmlspecialchars($_SESSION['users_FirstName']) . '</li>
                    <li>
                  <a href="#">About Us</a>
              </li>
              <li>
                  <a href="#">FAQs</a>
              </li>
              <li>
                  <a href="Ts&Cs.html">Ts and Cs</a>
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
                     <a href="Ts&Cs.html">Ts and Cs</a>
                     </li>
                     </ul>
                    </nav>';
                }
                ?>
                      
                      
               
            </ul>
        </nav>
    
        <section class="images">
            
             
              <img src="images/alyssa-strohmann-TS--uNw-JqE-unsplash.jpg" alt="images/alyssa-strohmann-TS--uNw-JqE-unsplash">
            </div>
            <div class="text">
                <h3>E-SHOP</h3>
                <P>Welcome To Our E-Shop with variety of options to buy   <button class="btn01"><a href="shop.html" >.</a>Shop now</button> </P> 
            </section>
           <section class="featured-products">
            <h5>FEATURED PRODUCTS</h5>
            <span> <P >Our Trending Winter wear and Gadgets</P></span>
            <div class="ar">
            <?php
    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $productId = $row['product_id'];
            $imageData = $row['Image']; 
            $productName = htmlspecialchars($row['product_name'] ?? '');
            $price = htmlspecialchars($row['price'] ?? '');
           
          
            echo '<div class="items"  data-id = '.$productId.'>
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
                </div>
           </section>

           <section>

           </section>

          <footer>
            <div><h6>E-shop</h6><p>Our E-shop offers reliable and efficent delivery of a variety of products all across the world </p></div>
            <div><h6>Links</h6>
            <ul>
            <li><a href="shop.html">Shop</a></li>
            <li><a href="cart.html">Cart</a></li>
            <li><a href="ContactUs.html">Contact</a></li>
            <li><a href="AboutUs.html">About Us</a></li>
            </ul></div>
            <div><h6>services</h6>
                <ul>
                    <li>FAQs</li>
                    <li>Shipping Information</li>
                    <li>Returns & Exchanges</li>
                    <li>Order Tracking</li>
                </ul>
            </div>
            <div><h6>Contacts</h6>
                <ul>
                    <li><box-icon name='map'></box-icon>Northen-Region, Rumphi, Malawi</li>
                     <li><box-icon name='phone'></box-icon> (+265)885620896</li>  
                     <li><box-icon name='envelope' type='solid' ></box-icon> cen01-01-22@unilia.ac.mw</li>
                     <span>
                      <p>Follow Us</p>
                 <span id="Follow">   <li><box-icon type='logo' name='facebook-circle'></box-icon></li>
                     <li><box-icon name='instagram' type='logo' ></box-icon></li>
                     <li><box-icon name='whatsapp' type='logo' ></box-icon></li></span> 
                    </span>
                    </ul></div>
          </footer>
          <div class="rights">          <span>  <p ><box-icon name='copyright' ></box-icon>2024 E-SHOP. All Rights Reserved</p></span>
          </div>
  <script >
    document.addEventListener("DOMContentLoaded",function(){
        const products = document.querySelectorAll(".items");
        products.forEach(product=>{ 
          product.addEventListener("dblclick",function(){
          const ProductImg = product.querySelector('.ProductImg').src;
          const ProductName = product.querySelector(".description").textContent;
          const ProductPrice =product.querySelector(".Price-description").textContent;
          
          console.log("Storing product data:");
          console.log("Image:", ProductImg);
          console.log("Name:", ProductName);
          console.log("Price:", ProductPrice);

          sessionStorage.setItem("Product-Image",ProductImg);
          sessionStorage.setItem("Product-Price",ProductPrice);
          sessionStorage.setItem("Product-Name",ProductName);
         
          window.location.href ="product-details.html";
        });
        
          });
    });
 
    
    
    document.addEventListener("DOMContentLoaded",() =>{
    const productDetails = [];
  
 function addproductdetails(event){
    const product = event.target.closest(".items");
     if(product)
{       const productID = product.getAttribute('data-id');
        const name = product.querySelector(".description").textContent;
        const price = product.querySelector(".Price").textContent;
        const image = product.querySelector(".ProductImg").src;
        const Details = {
            ID: productID,
            Image: image,
            Name: name,
            Price: price
        };
      const existingIndex = productDetails.findIndex(Item => Item.Name === Details.Name);
      if(existingIndex !==-1){
        productDetails.splice(existingIndex, 1);
      }
        productDetails.push(Details);
        localStorage.setItem('SelectedProducts',JSON.stringify(productDetails));
        console.log(productDetails);
      } 
} 
const AddToCarts = document.querySelectorAll(".btn3");
AddToCarts.forEach(AddToCart =>{
    AddToCart.addEventListener("click",addproductdetails);
});
});


</script>
    </body>
</html>