<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Search = $_POST['search'];

    // Prepare and execute search query
    $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE product_name LIKE ?");
    $like = "%" . $Search . "%";
    $stmt->bind_param("s", $like);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();

    if ($count > 0) {
        // Redirect to the search result page with the query parameter
        header("Location: SearchResult.php?query=" . urlencode($Search));
        exit();
    } else {
        echo "<script>alert('No data found in the database');</script>";
    }

    $stmt->close();
}
$conn->close();
?>