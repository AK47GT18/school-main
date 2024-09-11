<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.html"); 
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "e-shop";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get form data
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <title>Checkout</title>
</head>
<body>
    <nav class="top-bar">
        <ul>
            <li>
                <p><b>E-SHOP</b></p>
            </li>
            <li><a href="index.php" class="active"><i><box-icon name='home-alt'></box-icon></i>Home</a></li>
            <li><a href="shop.php"><box-icon type='solid' name='shopping-bags'></box-icon>Shop</a></li>
            <li><a href="cart.php"><i><box-icon name='cart'></box-icon></i> Cart</a></li>
            <li><a href="Contact.php">Contact Us</a></li>

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
    
    <section class="cart">
        <form id="checkout-form" action="CheckOut.php" method="POST">
            <ul>
                <li><p>Items</p><input id="NumberOfCartItems" type="text" placeholder="0" readonly></li>
                <li><p>Price</p></li>
                <li><p>Quantity</p></li>
            </ul>
            <div class="cart-item-container"></div>

            <div class="cart-pricing">
                <label for="discount">Discount</label>
                <input type="text" name="discount">
                <label for="Total">Total</label>
                <input id="ProductTotal" type="text" placeholder="MWK 00" readonly>
                <input type="hidden" name="products" id="products">
                <input type="hidden" name="total_price" id="total_price">
            </div>

            <div>
            <button id="clear-cart">Clear Cart</button>
                <button class="check-out" id="CheckOut" type="submit">Check Out</button>
            </div>
        </form>
    </section>
    <script>
      document.addEventListener("DOMContentLoaded", () => {
    // Load cart products from localStorage
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
                <table>
                    <tr>
                        <td><img id="cart-img" src="${product.Image}" alt="${product.Name}"></td>
                        <td><label for="cart-img" id="Cart-ProductName">${product.Name}</label></td>
                        <td><label for="Price" id="Cart-ProductPrice">${product.Price}</label></td>
                        <td><input type="number" value="1" min="1" class="product-quantity" data-price="${product.Price}" data-id="${product.ID}"></td>
                        <td><button class="remove-item">Remove</button></td>
                    </tr>
                </table>`;
            cartItemsContainer.appendChild(productDiv);

            totalPrice += parseInt(product.Price.replace('MWK', ''));
        });

        updateTotalPrice(totalPrice);

        // Update hidden input fields with cart data
        document.getElementById("products").value = JSON.stringify(productList);
        document.getElementById("total_price").value = totalPrice;
    } else {
        document.querySelector(".cart-item-container").innerHTML = "No products in local storage";
    }

    // Event listener for removing individual products (using event delegation)
    document.querySelector(".cart-item-container").addEventListener("click", (event) => {
        if (event.target.classList.contains("remove-item")) {
            removeProduct(event);
        }
    });

    // Event listener for updating price when quantity changes
    document.querySelector(".cart-item-container").addEventListener("input", (event) => {
        if (event.target.classList.contains("product-quantity")) {
            updateQuantity(event);
        }
    });

    // Clear Cart button
    document.getElementById("clear-cart").addEventListener("click", (event) => {
        event.preventDefault(); 
        clearCart();
    });
});

// Function to update total price
function updateTotalPrice(totalPrice) {
    const productTotalInput = document.getElementById("ProductTotal");
    if (productTotalInput) {
        productTotalInput.value = `MWK ${totalPrice.toFixed(2)}`;
    }
}

// Remove individual product from cart and localStorage
function removeProduct(event) {
    const product = event.target.closest(".cart-items");
    if (product) {
        const productID = product.querySelector(".product-quantity").dataset.id;
        let productDetails = JSON.parse(localStorage.getItem('SelectedProducts')) || [];

        // Filter out the product with matching ID
        productDetails = productDetails.filter(item => item.ID !== productID);

        // Update localStorage with remaining products
        localStorage.setItem('SelectedProducts', JSON.stringify(productDetails));

        // Remove product from DOM
        product.remove();

        // Recalculate total price
        let newTotal = productDetails.reduce((acc, curr) => acc + parseInt(curr.Price.replace('MWK', '')), 0);
        updateTotalPrice(newTotal);

        // Update hidden fields
        document.getElementById("products").value = JSON.stringify(productDetails);
        document.getElementById("total_price").value = newTotal.toFixed(2);
    }
}

// Clear the entire cart
function clearCart() {
    localStorage.removeItem('SelectedProducts');
    document.querySelector(".cart-item-container").innerHTML = "No products in local storage";
    updateTotalPrice(0);

    // Update hidden fields
    document.getElementById("products").value = "";
    document.getElementById("total_price").value = "0.00";
}

// Update quantity and total price when user changes quantity
function updateQuantity(event) {
    const quantityInput = event.target;
    const newQuantity = parseInt(quantityInput.value);
    const pricePerItem = parseInt(quantityInput.dataset.price.replace('MWK', ''));
    const productID = quantityInput.dataset.id;

    let productDetails = JSON.parse(localStorage.getItem('SelectedProducts')) || [];

    // Find the product in localStorage and update quantity (if needed)
    const product = productDetails.find(item => item.ID === productID);
    if (product) {
        const oldPrice = parseInt(product.Price.replace('MWK', ''));
        const newPrice = pricePerItem * newQuantity;
        product.Price = `MWK ${newPrice.toFixed(2)}`;
        localStorage.setItem('SelectedProducts', JSON.stringify(productDetails));
    }

    // Recalculate total price
    let newTotal = productDetails.reduce((acc, curr) => acc + parseInt(curr.Price.replace('MWK', '')), 0);
    updateTotalPrice(newTotal);

    // Update hidden fields
    document.getElementById("products").value = JSON.stringify(productDetails);
    document.getElementById("total_price").value = newTotal;
}

    </script>
</body>
</html>