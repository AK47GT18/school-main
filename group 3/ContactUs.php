<?php

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'], $_POST['message'], $_POST['UserName'])) {
        $email = $_POST['email'];
        $message = $_POST['message'];
        $userName = $_POST['UserName'];

        if ($email) {
            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'websmtp47@gmail.com';
                $mail->Password   = 'jbvkukdacbphzaet'; 
                $mail->SMTPSecure = 'tls';
                $mail->Port       = 587;

                // Recipients
                $mail->setFrom($email, $userName);
                $mail->addAddress('websmtp47@gmail.com','Admin' );

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'User Query';
                $mail->Body    = $message;

                $mail->send();
                echo 'Message has been sent successfully!';
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        } else {
            echo 'Invalid email address provided.';
        }
    } else {
        echo 'Please fill in all the required fields.';
    }
}
?>
