<?php
session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.php"); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <a href="index.php" class="active"><i><box-icon name='home-alt'></box-icon></i>Home</a>
            </li>
            <li> <a href="shop.html"><box-icon type='solid' name='shopping-bags'></box-icon>Shop</a> </li>
            <li>  <a href="cart.html"> <i><box-icon name='cart' ></box-icon></i> Cart</a> </li>
            <li> <a href="ContactUs.html"> Contact Us </a></li>
        
        
            <div class="srch-bx">
                <li> <input type="search" placeholder="search" class="srh">
                <i><box-icon class="sch" name='search' ></box-icon></i> 
            </li> 
            </div>
        
        <input type="checkbox" id="menu-toggle">
        <label for="menu-toggle" class="ham">
                <span></span>
                <span></span>
                <span></span>
            </label>
            <nav class="sidebar">
    
                <ul>
                    <li>
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
                        <a href="#">Ts and Cs</a>
                    </li>
                </ul>
            </nav>
        </ul>
    </nav>
    <section class="cart">
   <ul>
    <li> <p>Items</p><input id="NumberOfCartItems" type="text" placeholder="0"></li>
    <li> <p>Price</p></li>
    <li> <p>Quantity</p></li>
   </ul>
  
   <div class="cart-item-container">
    <img id="cart-img" src="" alt="01">
    <label for="cart-img" id="Cart-ProductName"></label>
    <label for="Price" id="Cart-ProductPrice"></label>
    <input type="text"  id="NumberOfProducts">
   </div>


   <div class="cart-pricing">
    <label for="discount">Discount</label>
    <input type="text">
    <label for="Total">Total</label>
    <input id="ProductTotal" type="text" placeholder="MWK 00.00">
   </div>

   <div>
    <button class="check-out" id="CheckOut"  ><a  href="https://in.paychangu.com/web/payment/SC-l7OUbg" >Check Out</a></button>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const isLoggedIn = <?php echo json_encode(isset($_SESSION['loggedin']) && $_SESSION['loggedin']); ?>;
            const checkoutLink = document.getElementById('checkout-link');
        
            if (!isLoggedIn) {
                checkoutLink.addEventListener('click', (event) => {
                    event.preventDefault(); 
                    alert('You must be logged in to access this page.'); 
                    window.location.href = 'Login.php';
                });
            }
        });
        </script>
</div>
</section>
<script >document.addEventListener("DOMContentLoaded", () => {
    const storedProducts = localStorage.getItem('SelectedProducts');
    
    if (storedProducts) {
        const productList = JSON.parse(storedProducts);
        const cartItemsContainer = document.querySelector(".cart-item-container");
        cartItemsContainer.innerHTML = "";

        let totalPrice = 0;

        productList.forEach(product => {
            const productDiv = document.createElement("div");
            productDiv.classList.add("cart-items");
            productDiv.innerHTML = `
                <img id="cart-img" src="${product.Image}" alt="${product.Name}">
                <label for="cart-img" id="Cart-ProductName">${product.Name}</label>
                <label for="Price" id="Cart-ProductPrice">${product.Price}</label>
                <input type="text" placeholder ="0" id="NumberOfProducts">`;
            cartItemsContainer.appendChild(productDiv);

            
            totalPrice += parseFloat(product.Price.replace('MWK', ''));
        });

        
        const productTotalInput = document.getElementById("ProductTotal");
        if (productTotalInput) {
            productTotalInput.value = `MWK ${totalPrice.toFixed(2)}`;
        }
    } else {
        document.querySelector(".cart-item-container").innerHTML = "No products in local storage";
    }
});
</script>

</body>
</html>