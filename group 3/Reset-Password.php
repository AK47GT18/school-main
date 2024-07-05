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

if (isset($_POST['reset-request-submit'])) {
    $email = $_POST['email'];
    $sql = "SELECT UserID, FirstName FROM Users WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $userID = $row['UserID'];
        $userName = $row['FirstName'];
        $resetToken = bin2hex(random_bytes(32)); 
        $expiryTime = date("Y-m-d H:i:s", time() + (24 * 3600)); 

        $insertSql = "INSERT INTO PasswordResetRequests (UserID, Email, ResetToken, ExpiresAt) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("isss", $userID, $email, $resetToken, $expiryTime);
        $stmt->execute();
        
        $resetLink = "http://localhost/Reset-Password.php?token=" . $resetToken;
        $subject = "Password Reset Request";
        $message = "Dear $userName,\n\nYou have requested a password reset. Please click the following link to reset your password:\n\n$resetLink\n\nThis link is valid for 24 hours.\n\nIf you did not request this, please ignore this email.";
        $headers = "From: admin@localhost.com";
        echo "Password reset email sent to $email. Check your inbox (and spam folder).";
    } else {
        echo "No user found with that email address.";
    }
    $stmt->close();
}

$conn->close();
?>
