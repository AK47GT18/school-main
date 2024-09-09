<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: Login.php"); 
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
    $userId = $_SESSION['user_id']; // Assuming user ID is stored in the session
    $totalPrice = $_POST['total_price'];
    $products = $_POST['products']; // This should be a JSON-encoded array

    // Insert order into database
    $stmt = $conn->prepare("INSERT INTO orders (UserID, TotalPrice, Products) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $userId, $totalPrice, $products);

    if ($stmt->execute()) {
        echo "Order placed successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
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
            <li><a href="shop.html"><box-icon type='solid' name='shopping-bags'></box-icon>Shop</a></li>
            <li><a href="cart.html"><i><box-icon name='cart'></box-icon></i> Cart</a></li>
            <li><a href="ContactUs.html">Contact Us</a></li>

            <div class="srch-bx">
                <li>
                    <input type="search" placeholder="search" class="srh">
                    <i><box-icon class="sch" name='search'></box-icon></i>
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
                    <li><a href="Sign-up.html">Sign-Up</a></li>
                    <li><a href="Login.html">Login</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">FAQs</a></li>
                    <li><a href="#">Ts and Cs</a></li>
                </ul>
            </nav>
        </ul>
    </nav>
    <section class="cart">
        <form id="checkout-form" action="checkOut.php" method="POST">
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
                <input id="ProductTotal" type="text" placeholder="MWK 00.00" readonly>
                <input type="hidden" name="products" id="products">
                <input type="hidden" name="total_price" id="total_price">
            </div>

            <div>
                <button class="check-out" id="CheckOut" type="submit">Check Out</button>
            </div>
            <script src="https://www.paypal.com/sdk/js?client-id=ATDmUJc_BrEUewbOg-j-j_oKIkmkYsxsVkM_L-RZirDTPGOt_Mk6op0E5h0NAxiAsPpALG8Fy1r_RB-R&currency=USD"></script>
            <div id="paypal-button-container"></div>
        </form>
    </section>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
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
                                <td><input type="number" value="1" class="product-quantity" data-price="${product.Price}" data-name="${product.Name}"></td>
                            </tr>
                        </table>`;
                    cartItemsContainer.appendChild(productDiv);

                    totalPrice += parseFloat(product.Price.replace('MWK', ''));
                });

                const productTotalInput = document.getElementById("ProductTotal");
                if (productTotalInput) {
                    productTotalInput.value = `MWK ${totalPrice.toFixed(2)}`;
                }

                const productsField = document.getElementById("products");
                if (productsField) {
                    productsField.value = JSON.stringify(productList);
                }

                const totalPriceField = document.getElementById("total_price");
                if (totalPriceField) {
                    totalPriceField.value = totalPrice.toFixed(2);
                }
            } else {
                document.querySelector(".cart-item-container").innerHTML = "No products in local storage";
            }
        });

        paypal.Buttons({
            createOrder: function(data, actions) {
                return fetch('create_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        totalPrice: document.getElementById('total_price').value,
                        products: document.getElementById('products').value
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(orderID) {
                    if (!orderID.id) {
                        throw new Error('Order ID not received');
                    }
                    return orderID.id;
                }).catch(function(error) {
                    console.error('Error creating order:', error);
                });
            },
            onApprove: function(data, actions) {
                return fetch('capture_order.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        orderID: data.orderID
                    })
                }).then(function(res) {
                    return res.json();
                }).then(function(details) {
                    alert('Transaction completed by ' + details.payer.name.given_name);
                }).catch(function(error) {
                    console.error('Error capturing order:', error);
                });
            }
        }).render('#paypal-button-container');
    </script>
</body>
</html>