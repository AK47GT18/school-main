<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $RememberMe = isset($_POST['check']) ? true : false;

    // Validate inputs
    if (empty($Email) || empty($Password)) {
        echo "Fill in all fields";
    } else {
        // Prepare SQL statement
        $sql = "SELECT * FROM Users WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            // Bind parameters and execute query
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if user exists
            if ($result->num_rows > 0) {
                // Fetch user record
                $row = $result->fetch_assoc();

                // Verify password
                if (password_verify($Password, $row['Password'])) {
                    echo "Login successful";

                    // Handle Remember Me functionality
                    if ($RememberMe) {
                        $token = bin2hex(random_bytes(16)); // Generate a random token

                        // Update token in database
                        $updateTokenSql = "UPDATE Users SET Cookies = ? WHERE Email = ?";
                        $updateTokenStmt = $conn->prepare($updateTokenSql);
                        
                        if ($updateTokenStmt) {
                            $updateTokenStmt->bind_param("ss", $token, $Email);
                            $updateTokenStmt->execute();
                            $updateTokenStmt->close();
                        } else {
                            echo "Error preparing update statement: " . $conn->error;
                        }

                        // Set cookie with token (expires in 30 days)
                        setcookie('Cookies', $token, time() + (86400 * 30), '/');
                    }

                    // Redirect to index.html
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

        // Close statement
        $stmt->close();
    }

    // Close connection
    $conn->close();
}
?>
