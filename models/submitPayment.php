<?php
include 'db.php'; // Adjust the path if necessary

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointmentID = $_POST['appointmentID'];
    $patientID = $_POST['patientID'];
    $paymentAmount = $_POST['paymentAmount'];

    // Update payments table
    $sqlPayment = "INSERT INTO payments (patientID, appointmentID, paymentAmount, paymentStatus)
                   VALUES ('$patientID', '$appointmentID', '$paymentAmount', true)";
    if ($conn->query($sqlPayment) === false) {
        die("Error updating payment: " . $conn->error);
    }

    // Update appointments table to mark as archived
    $sqlArchive = "UPDATE appointments SET archived = true WHERE appointmentID = '$appointmentID'";
    if ($conn->query($sqlArchive) === false) {
        die("Error archiving appointment: " . $conn->error);
    }

    // Redirect back to Secretary Dashboard
    header("Location: ../pages/secretaryDashboard.php");
    exit();
} else {
    die("Unauthorized access");
}

$conn->close();
?>
