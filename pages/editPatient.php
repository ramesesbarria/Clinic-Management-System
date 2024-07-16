<?php
include '../models/db.php'; // Adjust the path if necessary

$patientID = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phone_number = $_POST['phone_number'];
    $email = $_POST['email'];

    $sql = "UPDATE patient SET
            first_name = '$first_name',
            last_name = '$last_name',
            dob = '$dob',
            address = '$address',
            phone_number = '$phone_number',
            email = '$email'
            WHERE patientID = $patientID";

    if ($conn->query($sql) === TRUE) {
        header("Location: viewPatients.php");
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }
} else {
    $sql = "SELECT * FROM patient WHERE patientID = $patientID";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $patient = $result->fetch_assoc();
    } else {
        echo "No patient found with ID: $patientID";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Patient</title>
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
        <h3 class="text-xl font-semibold mb-4">Edit Patient Information</h3>
        <form method="POST" action="editPatient.php?id=<?php echo $patientID; ?>">
            <div class="mb-4">
                <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo htmlspecialchars($patient['first_name']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo htmlspecialchars($patient['last_name']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                <input type="date" name="dob" id="dob" value="<?php echo htmlspecialchars($patient['dob']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input type="text" name="address" id="address" value="<?php echo htmlspecialchars($patient['address']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input type="text" name="phone_number" id="phone_number" value="<?php echo htmlspecialchars($patient['phone_number']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($patient['email']); ?>" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Update Patient</button>
            </div>
        </form>
    </div>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>
