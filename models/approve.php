<?php
include '../models/db.php'; // Adjust the path if necessary

// Get the appointment ID from the URL
$appointmentID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Update the appointment to approved
$sql = "UPDATE appointments SET approved = 1 WHERE appointmentID = $appointmentID";
if ($conn->query($sql) === true) {
    echo "Appointment approved successfully!";
} else {
    echo "Error: " . $conn->error;
}

// Redirect back to the dashboard
header("Location: ../pages/secretary.php");
exit();
?>
