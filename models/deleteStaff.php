<?php
session_start();
require '../models/db.php';
// Check if user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $staffID = $_GET['id'];
    $sql = "DELETE FROM staff WHERE staffID = $staffID";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../Pages/staffTable.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

?>