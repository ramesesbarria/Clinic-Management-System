<?php
session_start(); // Start the session

// Include your database connection
include 'db.php';

// Check if the form is submitted via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize form inputs (adjust validation as per your requirements)
    $date_preference = htmlspecialchars($_POST['date_preference']);
    $time_preference = htmlspecialchars($_POST['time_preference']);
    $reason = htmlspecialchars($_POST['reason']);
    $appointment_type = htmlspecialchars($_POST['appointment_type']);

    // Adjust appointment_type to match case-sensitive enum values
    switch (strtolower($appointment_type)) {
        case 'regular_checkup':
            $appointment_type = 'Regular Checkup';
            break;
        case 'specific_treatment':
            $appointment_type = 'Specific Treatment';
            break;
        case 'consultation':
            $appointment_type = 'Consultation';
            break;
        default:
            echo "Error: Invalid appointment type.";
            exit; // Exit script if invalid appointment type
    }

    // Get patientID from session (ensure session variable is set)
    if (isset($_SESSION['patientID'])) {
        $patientID = $_SESSION['patientID'];

        // Insert into appointments table
        $stmt = $conn->prepare("INSERT INTO appointments (patientID, date_preference, time_preference, appointment_type, reason) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $patientID, $date_preference, $time_preference, $appointment_type, $reason);

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
        // Handle case where patientID is not set in session
        echo "Error: patientID not found in session. Please log in again.";
    }
} else {
    // Redirect or handle if form submission method is not POST
    echo "Form submission method not allowed.";
}
?>
