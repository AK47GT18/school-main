<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastName'];
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $Country = $_POST['country'];
    $PhoneNumber = $_POST['PhoneNumber'];


    if (empty($firstName) || empty($lastName) || empty($Email) || empty($Password) || empty($Country) || empty($PhoneNumber)) {
        echo "Fill in all fields";
    } else {

        $sql = "SELECT * FROM Users WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("s", $Email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                echo "This email is already registered. Please use a different email.";
            } else {
    
                $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
                $insertSql = "INSERT INTO Users (firstName, lastName, Email, Password, Country, PhoneNumber) VALUES (?, ?, ?, ?, ?, ?)";
                $insertStmt = $conn->prepare($insertSql);
                
                if ($insertStmt) {
                    $insertStmt->bind_param("sssssi", $firstName, $lastName, $Email, $hashedPassword, $Country, $PhoneNumber);
                    if ($insertStmt->execute()) {
                        echo "Sign-up successful! You can now log in.";
                        header("Location: login.html"); // Redirect to login page after successful sign-up
                        exit();
                    } else {
                        echo "Error: " . $conn->error;
                    }
                    $insertStmt->close();
                } else {
                    echo "Error preparing statement: " . $conn->error;
                }
            }

            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

    // Close connection
    $conn->close();
}
?>
