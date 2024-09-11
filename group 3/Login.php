<?php
session_start(); 

// Display errors for debugging (remove or comment out in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

// Establish database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get input values and sanitize them
    $Email = filter_var($_POST['Email'], FILTER_SANITIZE_EMAIL);
    $Password = $_POST['Password'];
    $RememberMe = isset($_POST['check']) ? true : false; 
    
    // Check if the input values are empty
    if (empty($Email) || empty($Password)) {
        echo "Fill in all fields";
    } else {
        // Prepare SQL statement to prevent SQL injection
        $sql = "SELECT * FROM Users WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $result = $stmt->get_result();
            
            // Check if a user was found
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                
                // Verify the password
                if (password_verify($Password, $row['Password'])) {
                    // Set session variables
                    $_SESSION['loggedin'] = true; 
                    $_SESSION['user_email'] = $Email; 
                    $_SESSION['users_FirstName'] = $row['FirstName'];
                    $_SESSION['users_UserID'] = $row['UserID'];
                    $_SESSION['PhoneNumber'] = $row['PhoneNumber'];

                    // Check for admin credentials
                    $adminPassword = 'Arthony#47K'; // Ideally, this should be hashed and compared securely
                    if ($Password === $adminPassword) {
                        header("Location: AdminDashboard.php");
                        exit();
                    }

                    // Handle "Remember Me" functionality
                    if ($RememberMe) {
                        $token = bin2hex(random_bytes(16));
                        setcookie('RememberMe', $token, time() + (86400 * 30), '/', '', false, true); // HttpOnly flag for security
                    }

                    // Redirect to user dashboard
                    header("Location: index.php");
                    exit(); 
                } else {
                    echo "<script>alert('Invalid Email or Password');</script>";
                }
            } else {
                echo "Invalid email or password.";
            }

            $stmt->close();
        } else {
            echo "Error preparing SQL statement: " . $conn->error;
        }
    }
    $conn->close();
}
?>
