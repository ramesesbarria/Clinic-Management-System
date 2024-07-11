<?php
session_start(); // Start the session

// Include your database connection
include 'db.php';

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs (adjust validation as per your requirements)
    $date_preference = htmlspecialchars($_POST['date_preference']);
    $time_preference = htmlspecialchars($_POST['time_preference']);
    $appointment_type = htmlspecialchars($_POST['appointment_type']);
    $reason = htmlspecialchars($_POST['reason']);

    // Insert into appointments table
    $stmt = $conn->prepare("INSERT INTO appointments (patientID, date_preference, time_preference, appointment_type, reason) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $patientID, $date_preference, $time_preference, $appointment_type, $reason);

    // Get patientID from session (replace with your actual session handling)
    $patientID = $_SESSION['patientID']; // Example: You need to set this based on your session handling

    if ($stmt->execute()) {
        // Insertion into appointments table successful
        $appointmentID = $stmt->insert_id; // Get the auto-generated appointmentID

        // Close the statement
        $stmt->close();

        // Insert a placeholder patientRecord tied to this appointmentID
        $stmtPatientRecord = $conn->prepare("INSERT INTO patientRecord (patientID, appointmentID) VALUES (?, ?)");
        $stmtPatientRecord->bind_param("ii", $patientID, $appointmentID);

        if ($stmtPatientRecord->execute()) {
            // PatientRecord insertion successful
            echo "Appointment booked successfully.";
        } else {
            // Handle patientRecord insertion failure
            echo "Error inserting patientRecord: " . $stmtPatientRecord->error;
        }

        // Close the patientRecord statement
        $stmtPatientRecord->close();
    } else {
        // Handle insertion failure for appointments table
        echo "Error inserting appointment: " . $stmt->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // Redirect or handle if form submission method is not POST
    echo "Form submission method not allowed.";
}
?>
