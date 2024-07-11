<?php
include 'db.php'; // Adjust the path if necessary

// Get the appointment ID from the URL
$appointmentID = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($appointmentID > 0) {
    // Delete related rows in patientRecord first
    $sqlDeletePatientRecord = "DELETE FROM patientRecord WHERE appointmentID = $appointmentID";
    if ($conn->query($sqlDeletePatientRecord) === TRUE) {
        // Then delete the appointment
        $sqlDeleteAppointment = "DELETE FROM appointments WHERE appointmentID = $appointmentID";
        if ($conn->query($sqlDeleteAppointment) === TRUE) {
            header("Location: ../pages/secretary.php");
        } else {
            echo "Error deleting appointment: " . $conn->error;
        }
    } else {
        echo "Error deleting patient record: " . $conn->error;
    }
} else {
    echo "Invalid appointment ID.";
}

// Close the database connection
$conn->close();
?>
