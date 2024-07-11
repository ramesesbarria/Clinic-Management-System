<?php
session_start(); // Start the session

// Include your database connection
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate appointmentID and patientRecordID
    if (isset($_POST['appointmentID']) && isset($_POST['patientRecordID'])) {
        $appointmentID = $_POST['appointmentID'];
        $patientRecordID = $_POST['patientRecordID'];

        // Retrieve data from the form
        $date_preference = $_POST['date_preference'];
        $time_preference = $_POST['time_preference'];
        $appointment_type = $_POST['appointment_type'];
        $reason = $_POST['reason'];
        $chief_complaint = $_POST['chief_complaint'];
        $duration_severity = $_POST['duration_severity'];
        $general_appearance = $_POST['general_appearance'];
        $visible_signs = $_POST['visible_signs'];
        $medical_history = $_POST['medical_history'];
        $height = $_POST['height'];
        $weight = $_POST['weight'];
        $blood_pressure = $_POST['blood_pressure'];
        $pulse_rate = $_POST['pulse_rate'];
        $temperature = $_POST['temperature'];
        $respiratory_rate = $_POST['respiratory_rate'];
        $current_medications = $_POST['current_medications'];
        $past_medications = $_POST['past_medications'];
        $allergies = $_POST['allergies'];
        $major_past_illnesses = $_POST['major_past_illnesses'];

        // Update appointment table
        $stmtAppointment = $conn->prepare("UPDATE appointments SET date_preference=?, time_preference=?, appointment_type=?, reason=?, chief_complaint=?, duration_severity=?, general_appearance=?, visible_signs=? WHERE appointmentID=?");
        $stmtAppointment->bind_param("ssssssssi", $date_preference, $time_preference, $appointment_type, $reason, $chief_complaint, $duration_severity, $general_appearance, $visible_signs, $appointmentID);

        if ($stmtAppointment->execute()) {
            // Update patientRecord table
            $stmtPatientRecord = $conn->prepare("UPDATE patientRecord SET medical_history=?, height=?, weight=?, blood_pressure=?, pulse_rate=?, temperature=?, respiratory_rate=?, current_medications=?, past_medications=?, allergies=?, major_past_illnesses=? WHERE patientRecordID=?");
            $stmtPatientRecord->bind_param("sddsisdssssi", $medical_history, $height, $weight, $blood_pressure, $pulse_rate, $temperature, $respiratory_rate, $current_medications, $past_medications, $allergies, $major_past_illnesses, $patientRecordID);

            if ($stmtPatientRecord->execute()) {
                // Redirect to secretary.php after successful update
                header("Location: ../pages/secretary.php");
                exit();
            } else {
                echo "Error updating patientRecord: " . $stmtPatientRecord->error;
            }

            // Close statement
            $stmtPatientRecord->close();
        } else {
            echo "Error updating appointment: " . $stmtAppointment->error;
        }

        // Close statement
        $stmtAppointment->close();
    } else {
        echo "Appointment ID or Patient Record ID not provided.";
    }
} else {
    echo "Invalid request method.";
}

// Close the database connection
$conn->close();
?>
