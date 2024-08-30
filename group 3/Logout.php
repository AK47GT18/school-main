<?php
session_start(); 
session_unset();
session_destroy();


if (isset($_COOKIE['RememberMe'])) {
    setcookie('RememberMe', '', time() - 3600, '/'); 
}


header("Location: Login.html");
exit();
?>
