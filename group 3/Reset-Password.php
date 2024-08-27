<?php
// Include PHPMailer
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Your SMTP configuration
$smtp_host = 'smtp.gmail.com';
$smtp_port = 587; // Adjust the port accordingly
$smtp_username = 'websmtp47@gmail.com';
$smtp_password = 'jbvkukdacbphzaet'; // Use app password if needed

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

// Create a connection
$connection = mysqli_connect($servername, $username, $password, $dbname);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $token = bin2hex(random_bytes(10));
        $resetLink = "http://http://localhost/school-main/group%203/Confirm-Password.php?token=". $token;
        $expirationTime = date("Y-m-d H:i:s", strtotime("+15 minutes"));

        $insert_token_sql = "INSERT INTO password_reset_tokens (email, token, expirationTime) VALUES ('$email', '$token', '$expirationTime')";
        mysqli_query($connection, $insert_token_sql);

        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $smtp_host;
        $mail->Port = $smtp_port;
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_username;
        $mail->Password = $smtp_password;
        $mail->SMTPSecure = 'tls'; // or 'ssl' if using port 465

        $mail->setFrom('websmtp47@gmail.com', 'Unilia EShop');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset Request';
        $mail->Body = 'A password reset request was made. Click the following link to reset your password: ' . $resetLink . ' If this request was not made by you, please ignore this email.';

        if ($mail->send()) {
            echo '<script>alert("An email has been sent to your registered email address with instructions to reset your password.");</script>';
        } else {
            echo 'Error sending email: ' . $mail->ErrorInfo;
        }
    }
}
?>
