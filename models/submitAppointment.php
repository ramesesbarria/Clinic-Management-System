<?php
session_start();
include 'db.php'; // Include the database connection

// Get the patient ID from the session
$patient_id = $_SESSION['patientID'];

if (!isset($patient_id)) {
    die("Error: Patient ID is not set in the session.");
}

// Prepare and bind the SQL statement
$stmt = $conn->prepare("INSERT INTO appointments (patientID, date_preference, time_preference, appointment_type, reason) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $patient_id, $date_preference, $time_preference, $appointment_type, $reason);

// Set parameters and execute
$date_preference = $_POST['date_preference'];
$time_preference = $_POST['time_preference'];
$appointment_type = $_POST['appointment_type'];
$reason = $_POST['reason'];

if ($stmt->execute()) {
    echo "New appointment booked successfully!";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
