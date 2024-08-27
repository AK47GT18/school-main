<?php
// Display errors for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E-shop";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $RememberMe = isset($_POST['check']) ? true : false; 
}

// Check for empty fields
if (empty($Email) || empty($Password)) {
    echo "Fill in all fields";
} else {
    // Prepare SQL query
    $sql = "SELECT * FROM Users WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the user exists
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify password
            if (password_verify($Password, $row['Password'])) {
                echo "Login successful";
                
                // Set "Remember Me" cookie if checked
                if ($RememberMe) {
                    $token = bin2hex(random_bytes(16));
                    // Store the token in a cookie
                    setcookie('RememberMe', $token, time() + (86400 * 30), '/'); // Cookie expires in 30 days
                }

                // Redirect to the homepage after login
                header("Location: index.html");
                exit(); 
            } else {
                echo "Invalid password";
            }
        } else {
            echo "User not found";
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
