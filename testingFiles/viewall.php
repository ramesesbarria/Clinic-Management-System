<?php
// Include your database connection
include '../models/db.php';

// Fetch all patients
$patientsSql = "SELECT * FROM patient";
$patientsResult = $conn->query($patientsSql);

// Fetch all patient records
$patientRecordsSql = "SELECT * FROM patientRecord";
$patientRecordsResult = $conn->query($patientRecordsSql);

// Fetch all prescriptions
$prescriptionsSql = "SELECT * FROM prescription";
$prescriptionsResult = $conn->query($prescriptionsSql);

// Fetch all appointments
$appointmentsSql = "SELECT * FROM appointments";
$appointmentsResult = $conn->query($appointmentsSql);

// Fetch all staff
$staffSql = "SELECT * FROM staff";
$staffResult = $conn->query($staffSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Information</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
    <div class="max-w-7xl mx-auto bg-white p-6 rounded-lg shadow-md">

        <!-- Patients Table -->
        <h2 class="text-2xl font-bold mb-4">Patients</h2>
        <?php if ($patientsResult->num_rows > 0): ?>
            <table class="min-w-full divide-y divide-gray-200 mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DOB</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($patient = $patientsResult->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $patient['patientID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $patient['first_name']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $patient['last_name']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $patient['dob']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $patient['address']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $patient['phone_number']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $patient['email']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $patient['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No patients found.</p>
        <?php endif; ?>

        <!-- Patient Records Table -->
        <h2 class="text-2xl font-bold mb-4">Patient Records</h2>
        <?php if ($patientRecordsResult->num_rows > 0): ?>
            <table class="min-w-full divide-y divide-gray-200 mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Record ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medical History</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Height</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Pressure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pulse Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Temperature</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Respiratory Rate</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Medications</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Past Medications</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allergies</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Major Past Illnesses</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($record = $patientRecordsResult->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['patientRecordID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['patientID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['appointmentID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['medical_history'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['height'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['weight'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['blood_pressure'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['pulse_rate'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['temperature'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['respiratory_rate'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['current_medications'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['past_medications'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['allergies'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['major_past_illnesses'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['created_at']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $record['updated_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No patient records found.</p>
        <?php endif; ?>

        <!-- Prescriptions Table -->
        <h2 class="text-2xl font-bold mb-4">Prescriptions</h2>
        <?php if ($prescriptionsResult->num_rows > 0): ?>
            <table class="min-w-full divide-y divide-gray-200 mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prescription ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prescription Text</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor's Notes</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($prescription = $prescriptionsResult->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $prescription['prescriptionID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $prescription['patientID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $prescription['prescription_text'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $prescription['doctors_notes'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $prescription['diagnosis'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $prescription['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No prescriptions found.</p>
        <?php endif; ?>

        <!-- Appointments Table -->
        <h2 class="text-2xl font-bold mb-4">Appointments</h2>
        <?php if ($appointmentsResult->num_rows > 0): ?>
            <table class="min-w-full divide-y divide-gray-200 mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Preference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Preference</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chief Complaint</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration Severity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">General Appearance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Visible Signs</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Archived</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($appointment = $appointmentsResult->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['appointmentID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['patientID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['date_preference']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['time_preference']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['appointment_type']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['reason']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['chief_complaint'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['duration_severity'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['general_appearance'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['visible_signs'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['approved'] ? 'Yes' : 'No'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['archived'] ? 'Yes' : 'No'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $appointment['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>

        <!-- Staff Table -->
        <h2 class="text-2xl font-bold mb-4">Staff</h2>
        <?php if ($staffResult->num_rows > 0): ?>
            <table class="min-w-full divide-y divide-gray-200 mb-6">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">First Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while($staff = $staffResult->fetch_assoc()): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $staff['staffID']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $staff['fname'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $staff['lname'] ?? 'N/A'; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $staff['email']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $staff['staffType']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $staff['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No staff found.</p>
        <?php endif; ?>

    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
