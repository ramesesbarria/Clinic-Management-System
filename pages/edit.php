<?php
session_start(); // Start the session

// Include your database connection
include '../models/db.php';

// Check if appointmentID is provided in the URL
if (isset($_GET['id'])) {
    $appointmentID = $_GET['id'];

    // Fetch appointment details
    $stmt = $conn->prepare("SELECT a.appointmentID, a.date_preference, a.time_preference, a.appointment_type, a.reason, a.chief_complaint, a.duration_severity, a.general_appearance, a.visible_signs, pr.patientRecordID, pr.medical_history, pr.height, pr.weight, pr.blood_pressure, pr.pulse_rate, pr.temperature, pr.respiratory_rate, pr.current_medications, pr.past_medications, pr.allergies, pr.major_past_illnesses FROM appointments AS a LEFT JOIN patientRecord AS pr ON a.appointmentID = pr.appointmentID WHERE a.appointmentID = ?");
    $stmt->bind_param("i", $appointmentID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $appointment = $result->fetch_assoc();

        // Check if patientRecordID exists
        if ($appointment['patientRecordID'] === null) {
            // If patientRecordID doesn't exist, create a placeholder entry
            $stmtCreatePatientRecord = $conn->prepare("INSERT INTO patientRecord (patientID, appointmentID) VALUES (?, ?)");
            $stmtCreatePatientRecord->bind_param("ii", $patientID, $appointmentID);

            // Get patientID from session (replace with your actual session handling)
            $patientID = $_SESSION['patientID']; // Example: You need to set this based on your session handling

            if ($stmtCreatePatientRecord->execute()) {
                $appointment['patientRecordID'] = $stmtCreatePatientRecord->insert_id;
            } else {
                echo "Error creating patientRecord: " . $stmtCreatePatientRecord->error;
                exit(); // Exit if creation fails
            }

            // Close statement
            $stmtCreatePatientRecord->close();
        }

        // Close statement
        $stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Edit Appointment</h2>
    <form action="../models/updateAppointment.php" method="POST" class="space-y-4">
        <input type="hidden" name="appointmentID" value="<?php echo $appointment['appointmentID']; ?>">
        <input type="hidden" name="patientRecordID" value="<?php echo $appointment['patientRecordID']; ?>">

        <!-- Appointment Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="date_preference" class="block text-sm font-medium text-gray-700">Date Preference:</label>
                <input type="date" id="date_preference" name="date_preference" value="<?php echo $appointment['date_preference']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="time_preference" class="block text-sm font-medium text-gray-700">Time Preference:</label>
                <input type="time" id="time_preference" name="time_preference" value="<?php echo $appointment['time_preference']; ?>" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="appointment_type" class="block text-sm font-medium text-gray-700">Appointment Type:</label>
                <select id="appointment_type" name="appointment_type" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="regular_checkup" <?php if ($appointment['appointment_type'] === 'regular_checkup') echo 'selected'; ?>>Regular Check-up</option>
                    <option value="specific_treatment" <?php if ($appointment['appointment_type'] === 'specific_treatment') echo 'selected'; ?>>Specific Treatment</option>
                    <option value="consultation" <?php if ($appointment['appointment_type'] === 'consultation') echo 'selected'; ?>>Consultation</option>
                </select>
            </div>
            <div class="col-span-2">
                <label for="reason" class="block text-sm font-medium text-gray-700">Reason for Appointment:</label>
                <textarea id="reason" name="reason" rows="4" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['reason']; ?></textarea>
            </div>
            <div class="col-span-2">
                <label for="chief_complaint" class="block text-sm font-medium text-gray-700">Chief Complaint:</label>
                <textarea id="chief_complaint" name="chief_complaint" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['chief_complaint']; ?></textarea>
            </div>
            <div class="col-span-2">
                <label for="duration_severity" class="block text-sm font-medium text-gray-700">Duration and Severity:</label>
                <textarea id="duration_severity" name="duration_severity" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['duration_severity']; ?></textarea>
            </div>
            <div class="col-span-2">
                <label for="general_appearance" class="block text-sm font-medium text-gray-700">General Appearance:</label>
                <textarea id="general_appearance" name="general_appearance" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['general_appearance']; ?></textarea>
            </div>
            <div class="col-span-2">
                <label for="visible_signs" class="block text-sm font-medium text-gray-700">Visible Signs:</label>
                <textarea id="visible_signs" name="visible_signs" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['visible_signs']; ?></textarea>
            </div>
        </div>

        <!-- Patient Record Details -->
        <h2 class="text-2xl font-bold mt-8 mb-4">Patient Record</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="medical_history" class="block text-sm font-medium text-gray-700">Medical History:</label>
                <textarea id="medical_history" name="medical_history" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['medical_history']; ?></textarea>
            </div>
            <div>
                <label for="height" class="block text-sm font-medium text-gray-700">Height:</label>
                <input type="number" step="0.01" id="height" name="height" value="<?php echo $appointment['height']; ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="weight" class="block text-sm font-medium text-gray-700">Weight:</label>
                <input type="number" step="0.01" id="weight" name="weight" value="<?php echo $appointment['weight']; ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="blood_pressure" class="block text-sm font-medium text-gray-700">Blood Pressure:</label>
                <input type="text" id="blood_pressure" name="blood_pressure" value="<?php echo $appointment['blood_pressure']; ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="pulse_rate" class="block text-sm font-medium text-gray-700">Pulse Rate:</label>
                <input type="number" id="pulse_rate" name="pulse_rate" value="<?php echo $appointment['pulse_rate']; ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="temperature" class="block text-sm font-medium text-gray-700">Temperature:</label>
                <input type="number" step="0.1" id="temperature" name="temperature" value="<?php echo $appointment['temperature']; ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div>
                <label for="respiratory_rate" class="block text-sm font-medium text-gray-700">Respiratory Rate:</label>
                <input type="number" id="respiratory_rate" name="respiratory_rate" value="<?php echo $appointment['respiratory_rate']; ?>" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
            </div>
            <div class="col-span-2">
                <label for="current_medications" class="block text-sm font-medium text-gray-700">Current Medications:</label>
                <textarea id="current_medications" name="current_medications" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['current_medications']; ?></textarea>
            </div>
            <div class="col-span-2">
                <label for="past_medications" class="block text-sm font-medium text-gray-700">Past Medications:</label>
                <textarea id="past_medications" name="past_medications" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['past_medications']; ?></textarea>
            </div>
            <div class="col-span-2">
                <label for="allergies" class="block text-sm font-medium text-gray-700">Allergies:</label>
                <textarea id="allergies" name="allergies" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['allergies']; ?></textarea>
            </div>
            <div class="col-span-2">
                <label for="major_past_illnesses" class="block text-sm font-medium text-gray-700">Major Past Illnesses:</label>
                <textarea id="major_past_illnesses" name="major_past_illnesses" rows="4" class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?php echo $appointment['major_past_illnesses']; ?></textarea>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <button type="submit" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Changes</button>
            <a href="secretary.php" class="inline-block bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded ml-2">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>

<?php
    } else {
        echo "Appointment not found.";
    }
} else {
    echo "Appointment ID not provided.";
}

// Close the database connection
$conn->close();
?>
