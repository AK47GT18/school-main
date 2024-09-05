<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ProductName = $_POST['itemName'];
    $ProductPrice = $_POST['itemPrice'];
    $ProductDescription = $_POST['itemDescription'];
    $ProductCategory = $_POST['itemCategory'];
    $ProductInventory = $_POST['itemInventory'];
    $ProductImage = $_POST['itemImage'];
        $stmt = $conn->prepare("INSERT INTO products (product_name, Image, price, description, category, inventory_stock) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $ProductName, $ProductImage, $ProductPrice, $ProductDescription, $ProductCategory, $ProductInventory);

        if ($stmt->execute()) {
            echo "Product successfully uploaded to the database.";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    

}

$conn->close();
?>
