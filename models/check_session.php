<?php
session_start();

// Check if the user is logged in, redirect to login page if not
if (!isset($_SESSION['user_id'])) {
    header("Location: loginForm.php");
    exit();
} else {
    $loggedIn = true;
}


?>
