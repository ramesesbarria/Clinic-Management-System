<?php
// fetch_prescriptions.php

// Include your database connection file (assuming it's already included)
include 'db.php';

if (isset($_GET['appointmentID'])) {
    $appointmentID = $_GET['appointmentID'];

    // Fetch prescriptions for the appointment
    $queryPrescriptions = "SELECT p.prescription_text, p.doctors_notes, p.diagnosis
                           FROM prescription p
                           JOIN appointments a ON p.patientID = a.patientID
                           WHERE a.appointmentID = ?
                             AND DATE(p.created_at) = a.date_preference
                           LIMIT 1";
    
    $stmt = $conn->prepare($queryPrescriptions);
    $stmt->bind_param("i", $appointmentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Output prescription details
        $row = $result->fetch_assoc();
        echo "<p><strong>Prescription:</strong> " . htmlspecialchars($row['prescription_text']) . "</p>";
        echo "<p><strong>Doctor's Notes:</strong> " . htmlspecialchars($row['doctors_notes']) . "</p>";
        echo "<p><strong>Diagnosis:</strong> " . htmlspecialchars($row['diagnosis']) . "</p>";
    } else {
        echo "<p>No prescription found for this appointment.</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
