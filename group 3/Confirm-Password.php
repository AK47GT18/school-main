<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

// Create a connection
$connection = mysqli_connect($servername, $username, $password, $dbname);
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['token'])) {
    $token = $_GET['token'];


    $query = "SELECT email, expirationTime FROM password_reset_tokens WHERE token = '$token'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $email = $row['email'];
        $expirationTime = $row['expirationTime'];

        if (new DateTime() < new DateTime($expirationTime)) {
    
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $newPassword = $_POST['Password'];
                $confirmPassword = $_POST['Confirm-Password'];

                if ($newPassword == $confirmPassword) {
             
                    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

                    $updatePasswordSql = "UPDATE users SET password = '$hashedPassword' WHERE email = '$email'";
                    if (mysqli_query($connection, $updatePasswordSql)) {
                        header('Location: Login.html');
                        exit();
                        $deleteTokenSql = "DELETE FROM password_reset_tokens WHERE token = '$token'";
                        mysqli_query($connection, $deleteTokenSql);
                       
                    } 
                  else {
                        echo "Error updating password: " . mysqli_error($connection);
                    }
                } else {
                    echo "Passwords do not match. Please try again.";
                }
            }
           
        } else {
            echo "This token has expired. Please request a new password reset.";
        }
    } else {
        echo "Invalid token.";
    }
} else {
    echo "No token provided.";
}

mysqli_close($connection);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="author" content="group 3">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="main.css">
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    <title>Reset Password</title>
</head>
<body class="log">
    <img class="cover" src="images\david-lezcano-NfZiOJzZgcg-unsplash.jpg" alt="image1">
    <div class="container3">
        <form action="" method="post">
            <div class="input-box3">
                <h1>Confirm Password</h1>
                <input type="password" name="Password" placeholder="Enter New Password" required>
                <input type="password" name="Confirm-Password" placeholder="Confirm New Password" required>
            </div>
            <input type="submit" class="btn3" value="Change Password">
        </form>
    </div>
</body>
</html>


