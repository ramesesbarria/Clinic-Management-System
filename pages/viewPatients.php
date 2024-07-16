<?php
include '../models/db.php'; // Adjust the path if necessary

// Initialize filter variables
$filterName = isset($_POST['filterName']) ? $_POST['filterName'] : '';
$filterContact = isset($_POST['filterContact']) ? $_POST['filterContact'] : '';

// Build the SQL query with filters
$sql = "SELECT p.patientID, p.first_name, p.last_name, p.dob, p.address, p.phone_number, p.email, p.created_at
        FROM patient p
        LEFT JOIN appointments a ON p.patientID = a.patientID
        WHERE 1=1";

if (!empty($filterName)) {
    $sql .= " AND (p.first_name LIKE '%$filterName%' OR p.last_name LIKE '%$filterName%')";
}

if (!empty($filterContact)) {
    $sql .= " AND (p.phone_number LIKE '%$filterContact%' OR p.email LIKE '%$filterContact%')";
}

if (!empty($filterAppointmentType)) {
    $sql .= " AND a.appointment_type = '$filterAppointmentType'";
}

$sql .= " GROUP BY p.patientID ORDER BY p.last_name, p.first_name ASC";

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
    <title>Patients List</title>
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
        <h3 class="text-xl font-semibold mb-4">Patients List</h3>

        <!-- Filter Form -->
        <form method="POST" action="viewPatients.php" class="mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="filterName" class="block text-sm font-medium text-gray-700">Filter by Name</label>
                    <input type="text" name="filterName" id="filterName" value="<?php echo htmlspecialchars($filterName); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter patient name">
                </div>
                <div>
                    <label for="filterContact" class="block text-sm font-medium text-gray-700">Filter by Contact</label>
                    <input type="text" name="filterContact" id="filterContact" value="<?php echo htmlspecialchars($filterContact); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Enter phone number or email">
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Apply Filters</button>
            </div>
        </form>

        <!-- Patients Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Date of Birth</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Address</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Created At</th>
                        <th class="px-6 py-3 text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php while($row = $result->fetch_assoc()) { ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['dob']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['address']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['phone_number']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['email']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap"><?php echo htmlspecialchars($row['created_at']); ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="editPatient.php?id=<?php echo $row['patientID']; ?>" class="text-indigo-600 hover:text-indigo-900">Edit</a>
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
