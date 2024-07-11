<?php
include '../models/db.php'; // Adjust the path if necessary

// Get the appointment ID from the URL
$appointmentID = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the appointment details from the database
$sql = "SELECT a.*, p.first_name, p.last_name, pr.medical_history, pr.height, pr.weight, pr.blood_pressure, pr.pulse_rate, pr.temperature, pr.respiratory_rate, pr.current_medications, pr.past_medications, pr.allergies, pr.major_past_illnesses 
        FROM appointments a 
        INNER JOIN patient p ON a.patientID = p.patientID 
        LEFT JOIN patientRecord pr ON a.appointmentID = pr.appointmentID 
        WHERE a.appointmentID = $appointmentID";

$result = $conn->query($sql);

// Check if query executed successfully and if the appointment exists
if ($result === false) {
    die("Error: " . $conn->error);
} elseif ($result->num_rows == 0) {
    die("Error: Appointment not found.");
}

$appointment = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">View Appointment</h2>

    <div class="grid grid-cols-2 gap-4">
        <div>
            <strong>Patient Name:</strong>
            <p><?php echo $appointment['first_name'] . ' ' . $appointment['last_name']; ?></p>
        </div>
        <div>
            <strong>Date Preference:</strong>
            <p><?php echo $appointment['date_preference']; ?></p>
        </div>
        <div>
            <strong>Time Preference:</strong>
            <p><?php echo $appointment['time_preference']; ?></p>
        </div>
        <div>
            <strong>Appointment Type:</strong>
            <p><?php echo $appointment['appointment_type']; ?></p>
        </div>
        <div>
            <strong>Reason:</strong>
            <p><?php echo $appointment['reason']; ?></p>
        </div>
        <div>
            <strong>Chief Complaint:</strong>
            <p><?php echo $appointment['chief_complaint']; ?></p>
        </div>
        <div>
            <strong>Duration & Severity:</strong>
            <p><?php echo $appointment['duration_severity']; ?></p>
        </div>
        <div>
            <strong>General Appearance:</strong>
            <p><?php echo $appointment['general_appearance']; ?></p>
        </div>
        <div>
            <strong>Visible Signs:</strong>
            <p><?php echo $appointment['visible_signs']; ?></p>
        </div>
        <div>
            <strong>Approved:</strong>
            <p><?php echo $appointment['approved'] ? 'Approved' : 'Not Approved'; ?></p>
        </div>

        <!-- Patient Record Details -->
        <div>
            <strong>Medical History:</strong>
            <p><?php echo $appointment['medical_history']; ?></p>
        </div>
        <div>
            <strong>Height:</strong>
            <p><?php echo $appointment['height']; ?> cm</p>
        </div>
        <div>
            <strong>Weight:</strong>
            <p><?php echo $appointment['weight']; ?> kg</p>
        </div>
        <div>
            <strong>Blood Pressure:</strong>
            <p><?php echo $appointment['blood_pressure']; ?></p>
        </div>
        <div>
            <strong>Pulse Rate:</strong>
            <p><?php echo $appointment['pulse_rate']; ?> bpm</p>
        </div>
        <div>
            <strong>Temperature:</strong>
            <p><?php echo $appointment['temperature']; ?> °C</p>
        </div>
        <div>
            <strong>Respiratory Rate:</strong>
            <p><?php echo $appointment['respiratory_rate']; ?> breaths/min</p>
        </div>
        <div>
            <strong>Current Medications:</strong>
            <p><?php echo $appointment['current_medications']; ?></p>
        </div>
        <div>
            <strong>Past Medications:</strong>
            <p><?php echo $appointment['past_medications']; ?></p>
        </div>
        <div>
            <strong>Allergies:</strong>
            <p><?php echo $appointment['allergies']; ?></p>
        </div>
        <div>
            <strong>Major Past Illnesses:</strong>
            <p><?php echo $appointment['major_past_illnesses']; ?></p>
        </div>
    </div>

    <a href="secretary.php" class="mt-6 inline-block text-blue-600 hover:underline">Back to Dashboard</a>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
