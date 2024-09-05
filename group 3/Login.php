<?php
session_start(); 


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
    $Email = $_POST['Email'];
    $Password = $_POST['Password'];
    $RememberMe = isset($_POST['check']) ? true : false; 
    
    
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
                
                    $_SESSION['loggedin'] = true; 
                    $_SESSION['user_email'] = $Email; 
                    $adminPassword = 'Arthony#47K';
                    if ($Password === $adminPassword) {
                        header("Location: AdminDashboard.html");
                        exit();
                    }
                    if ($RememberMe) {
                        $token = bin2hex(random_bytes(16));
                        setcookie('RememberMe', $token, time() + (86400 * 30), '/'); 
                    }

                    header("Location: index.html");
                    exit(); 
                } else {
                    echo "<script>
                    alert('Invalid Email or Password');
                    </script>";
                }
            } else {
                echo "Invalid email or password.";
            }
        }
    }
    $conn->close();
}
?>

