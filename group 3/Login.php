<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "E-shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $RememberMe = isset($_POST['check']) ? true : false; 
}

if (empty($Email) || empty($Password)) {
    echo "Fill in all fields";
} else {
    $sql = "SELECT * FROM Users WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            if (password_verify($Password, $row['Password'])) {
                echo "Login successful";
                
                if ($RememberMe) {
                    $token = bin2hex(random_bytes(16));
                    
                    $updateTokenSql = "UPDATE Users SET Cookies = ? WHERE Email = ?";
                    $updateTokenStmt = $conn->prepare($updateTokenSql);
                    if ($updateTokenStmt) {
                        $updateTokenStmt->bind_param("ss", $token, $Email);
                        $updateTokenStmt->execute();
                        $updateTokenStmt->close();
                    } else {
                        
                    }

                    setcookie('Cookies', $token, time() + (86400 * 30), '/'); // Cookie expires in 30 days
                }

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

    $stmt->close();
    $conn->close();
}
?>
