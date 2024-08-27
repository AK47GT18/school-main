<?php
// save_cart.php

header('Content-Type: application/json');

$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "e-shop"; // Replace with your database name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
}

$data = json_decode(file_get_contents('php://input'), true);
$items = $data['items'];

foreach ($items as $item) {
    $name = $conn->real_escape_string($item['name']);
    $price = $conn->real_escape_string($item['price']);
    $quantity = $conn->real_escape_string($item['quantity']);

    $sql = "INSERT INTO cart_items (item_name, price, quantity) VALUES ('$name', '$price', '$quantity')";

    if (!$conn->query($sql)) {
        echo json_encode(['success' => false, 'message' => 'Error: ' . $conn->error]);
        $conn->close();
        exit();
    }
}

echo json_encode(['success' => true, 'message' => 'Items successfully added to the database!']);
$conn->close();
?>
