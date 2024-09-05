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
   
        $stmt= $conn->prepare("DELETE FROM products WHERE product_name = ? ");
        $stmt->bind_param("s", $ProductName);
        if ( $stmt->execute()) {
            echo"Product Removed";
        }
        $stmt->close();
    } 
$conn->close();
?>
