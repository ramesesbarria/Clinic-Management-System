<?php
session_start();

// Check if the user is logged in, redirect to login page if not
if (!isset($_SESSION['patientID'])) {
    header("Location: loginForm.php");
    exit();
} else {
    $loggedIn = true;
}


?>
