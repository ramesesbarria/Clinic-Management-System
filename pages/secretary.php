<?php
include '../models/db.php'; // Adjust the path if necessary

// Fetch appointments with patient details from the database
$sql = "SELECT a.appointmentID, a.patientID, a.date_preference, a.time_preference, a.appointment_type, a.reason, a.chief_complaint, a.duration_severity, a.general_appearance, a.visible_signs, a.approved, p.first_name, p.last_name 
        FROM appointments a 
        INNER JOIN patient p ON a.patientID = p.patientID 
        ORDER BY a.date_preference, a.time_preference ASC";

$result = $conn->query($sql);

// Check if query executed successfully
if ($result === false) {
    die("Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secretary Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6">Secretary Dashboard</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full max-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Preference</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Preference</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Approved</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['date_preference']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['time_preference']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['appointment_type']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['reason']; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['approved'] ? 'Approved' : 'Not Approved'; ?></td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="view.php?id=<?php echo $row['appointmentID']; ?>" class="text-blue-600 hover:underline">View</a>
                            <a href="edit.php?id=<?php echo $row['appointmentID']; ?>" class="text-green-600 hover:underline ml-4">Edit</a>
                            <a href="approve.php?id=<?php echo $row['appointmentID']; ?>" class="text-green-600 hover:underline ml-4">Approve</a>
                            <a href="reject.php?id=<?php echo $row['appointmentID']; ?>" class="text-red-600 hover:underline ml-4">Reject</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
