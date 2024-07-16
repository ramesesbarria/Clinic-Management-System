<?php
include 'checkSession.php';
include 'db.php'; // Include your database connection script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate appointmentID and other form fields
    if (isset($_POST['appointmentID'])) {
        $appointmentID = $_POST['appointmentID'];

        // Retrieve data from the form
        $date_preference = $_POST['date_preference'];
        $time_preference = $_POST['time_preference'];
        $appointment_type = $_POST['appointment_type'];
        $reason = $_POST['reason'];

        // Update appointment in the database
        $stmt = $conn->prepare("UPDATE appointments SET date_preference=?, time_preference=?, appointment_type=?, reason=?, approved=0 WHERE appointmentID=?");
        $stmt->bind_param("ssssi", $date_preference, $time_preference, $appointment_type, $reason, $appointmentID);

        if ($stmt->execute()) {
            // Determine where to redirect based on the current page
            $redirectPage = '../pages/landing_page.php'; // Default redirect page

            if (strpos($_SERVER['HTTP_REFERER'], 'appointment_history.php') !== false) {
                $redirectPage = '../pages/appointment_history.php';
            }

            // Redirect back to appropriate page
            header("Location: " . $redirectPage);
            exit();
        } else {
            echo "Error updating appointment: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Appointment ID not provided.";
    }
} else {
    echo "Invalid request method.";
}

$conn->close(); // Close the database connection
?>