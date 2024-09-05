<?php
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
        <!-- Navigation bar code here -->
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
</body>
</html>
