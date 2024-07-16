<?php
// Include your database connection or any necessary files
include 'db.php';

// Check if appointmentID is received through POST
if (isset($_POST['appointmentID'])) {
    $appointmentID = $_POST['appointmentID'];

    // Prepare SQL statement to delete the appointment
    $sql = "DELETE FROM appointments WHERE appointmentID = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt === false) {
        // Handle the case where the query preparation failed
        echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
        exit;
    }

    // Bind parameters and execute the statement
    mysqli_stmt_bind_param($stmt, 'i', $appointmentID);
    $result = mysqli_stmt_execute($stmt);

    // Check if deletion was successful
    if ($result) {
        echo json_encode(['success' => true, 'message' => 'Appointment canceled successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to cancel appointment']);
    }

    // Close statement and database connection
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
} else {
    // Handle case where appointmentID is not received
    echo json_encode(['success' => false, 'message' => 'AppointmentID not provided']);
}
?>