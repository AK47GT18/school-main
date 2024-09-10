<?php
$servername ="localhost";
$username = "root";
$password = "";
$dbname = "e-shop";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$stmt = $conn->prepare("SELECT * FROM users WHERE roles ='customer'");
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="Viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <table>
        <tr>
        <td>UserID</td>
        <td>User Name</td>
        <td>User Email</td>
        <td>Delete User</td>
        </tr>
        <?php
        while($row = $result->fetch_assoc()){
        echo '
         <tr>
         <td>'.$row['UserID'].'</td>
         <td>'.$row['FirstName'].' '.$row['LastName'].'</td>
         <td>'.$row['Email'].'</td>
         <form action ="" method ="post">
         <input type="hidden" name="UserID" value="'.$row['UserID'].'">
         <td><button type ="submit">Delete</button></td>
         </tr>
         </form>';}
        ?>
        <?php
$servername ="localhost";
$username = "root";
$password = "";
$dbname = "e-shop";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['UserID'];
    $stmt = $conn->prepare("DELETE FROM users WHERE UserID = ?");
    $stmt->bind_param("i", $userID);

    if ($stmt->execute()) {
        header("Location: UserManagment.php");
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}

$conn->close();
?>

    </table>
</body>
</html>