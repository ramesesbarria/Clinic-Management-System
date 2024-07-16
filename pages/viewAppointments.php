<?php
include '../models/db.php'; // Adjust the path if necessary

// Initialize filter variables
$filterDate = isset($_POST['filterDate']) ? $_POST['filterDate'] : '';
$filterPatient = isset($_POST['filterPatient']) ? $_POST['filterPatient'] : '';
$filterAppointmentType = isset($_POST['filterAppointmentType']) ? $_POST['filterAppointmentType'] : '';

// Build the SQL query with filters
$sql = "SELECT a.appointmentID, a.patientID, a.date_preference, a.time_preference, a.appointment_type, a.reason, a.chief_complaint, a.duration_severity, a.general_appearance, a.visible_signs, a.archived, p.first_name, p.last_name
        FROM appointments a
        INNER JOIN patient p ON a.patientID = p.patientID
        WHERE 1=1";

if (!empty($filterDate)) {
    $sql .= " AND a.date_preference = '$filterDate'";
}

if (!empty($filterPatient)) {
    $sql .= " AND (p.first_name LIKE '%$filterPatient%' OR p.last_name LIKE '%$filterPatient%')";
}

if (!empty($filterAppointmentType)) {
    $sql .= " AND a.appointment_type = '$filterAppointmentType'";
}

$sql .= " ORDER BY a.date_preference, a.time_preference ASC";

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
    <title>Appointments List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-image: url('../img/background.png'); /* Replace with your actual path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .sidebar {
            width: 250px;
            background-color: #1a202c; /* Tailwind 'gray-800' */
            color: white;
            padding: 20px;
            flex-shrink: 0;
        }
        .sidebar a {
            display: block;
            padding: 10px 0;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #2d3748; /* Tailwind 'gray-700' */
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="logo"> <img src="../img/logo.png" alt="Logo"> </div>
        <b> </b>
        <h2 class="text-xl font-bold mb-6">Secretary Dashboard</h2>
        <a href="secretaryDashboard.php">Main Dashboard</a>
        <a href="clinicStats.php">Clinic Stats</a>
        <a href="viewAppointments.php">Appointments Lists</a>
        <a href="viewPatients.php">Patients list</a>
        <a href="../models/handleLogout.php">Log Out</a>
    </div>
    <div class="content">
        <h3 class="text-xl font-semibold mb-4">Appointments List</h3>

        <!-- Filter Form -->
        <form method="POST" action="viewAppointments.php" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="filterDate" class="block text-sm font-medium text-gray-700">Filter by Date</label>
                    <input type="date" name="filterDate" id="filterDate" value="<?php echo $filterDate; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label for="filterPatient" class="block text-sm font-medium text-gray-700">Filter by Patient</label>
                    <input type="text" name="filterPatient" id="filterPatient" value="<?php echo $filterPatient; ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter patient name">
                </div>
                <div>
                    <label for="filterAppointmentType" class="block text-sm font-medium text-gray-700">Filter by Appointment Type</label>
                    <select name="filterAppointmentType" id="filterAppointmentType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select type</option>
                        <option value="Regular Checkup" <?php echo $filterAppointmentType == 'Regular Checkup' ? 'selected' : ''; ?>>Regular Checkup</option>
                        <option value="Specific Treatment" <?php echo $filterAppointmentType == 'Specific Treatment' ? 'selected' : ''; ?>>Specific Treatment</option>
                        <option value="Consultation" <?php echo $filterAppointmentType == 'Consultation' ? 'selected' : ''; ?>>Consultation</option>
                    </select>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Apply Filters</button>
            </div>
        </form>

        <!-- Appointments Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment Type</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Archived</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['date_preference']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['time_preference']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['appointment_type']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo $row['reason']; ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium leading-4 <?php echo $row['archived'] ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'; ?>">
                                    <?php echo $row['archived'] ? 'Archived' : 'Not Archived'; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="editAppointment.php?id=<?php echo $row['appointmentID']; ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
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
