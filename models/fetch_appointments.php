<?php
// Include necessary files
include 'db.php';
include 'check_session.php';

// Function to fetch recent appointments
function fetchAppointments() {
    global $conn; // Access the database connection

    // Example SQL query to fetch recent appointments for a logged-in user
    $userId = $_SESSION['user_id']; // Assuming you store user ID in the session

    $sql = "SELECT * FROM appointments WHERE patientID = $userId ORDER BY date_preference DESC LIMIT 10";
    $result = $conn->query($sql);

    $appointments = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $appointments[] = $row;
        }
    }

    return $appointments;
}

// Fetch appointments
$appointments = fetchAppointments();

// Output JSON response
header('Content-Type: application/json');
echo json_encode($appointments);
?>
