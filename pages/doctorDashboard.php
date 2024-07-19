<?php
session_start();
require '../models/db.php';
// Check if user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'doctor') {
    header("Location: index.php");
    exit();
}

// Fetch doctor's first name
$doctorId = $_SESSION['userID']; // Assuming userID in session represents the doctor's ID
$queryDoctor = "SELECT fname FROM staff WHERE staffID = $doctorId";
$resultDoctor = mysqli_query($conn, $queryDoctor);
$rowDoctor = mysqli_fetch_assoc($resultDoctor);
$doctorFirstName = $rowDoctor['fname'];

// Default query to fetch all 'approved' appointments
$queryAppointments = "SELECT
                        appointments.appointmentID,
                        appointments.date_preference,
                        appointments.time_preference,
                        appointments.reason,
                        patient.first_name AS patient_fname,
                        patient.last_name AS patient_lname
                      FROM appointments
                      INNER JOIN patient ON appointments.patientID = patient.patientID
                      WHERE appointments.approved = 1 AND appointments.completed = 0";

// Handling filters and search
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Date filter
    $dateFilter = $_POST['dateFilter'];
    if (!empty($dateFilter)) {
        $dateFilter = date('Y-m-d', strtotime($dateFilter));
        $queryAppointments .= " AND DATE(appointments.date_preference) = '$dateFilter'";
    }

    // Patient name search
    $patientName = $_POST['patientName'];
    if (!empty($patientName)) {
        $queryAppointments .= " AND (patient.first_name LIKE '%$patientName%' OR patient.last_name LIKE '%$patientName%')";
    }
}

$queryAppointments .= " ORDER BY appointments.date_preference ASC, appointments.time_preference ASC";

$resultAppointments = mysqli_query($conn, $queryAppointments);

// Function to logout user
function logout() {
    session_unset();
    session_destroy();
    header("Location: ..\pages\index.php");
    exit();
}

// Logout if logout button is clicked
if (isset($_POST['logout'])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        * {
            font-family: 'Merriweather', serif;
        }
        .navbar {
            justify-content: space-between;
        }
        body {
            background-image: url('../img/background.png'); /* Replace with your actual path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .navbar {
            background-color: #f8f9fa;
            position: relative;
            z-index: 1000; /* Ensure navbar is above other content */
        }
        .navbar-brand img {
            height: 100px;
        }
        .navbar .dropdown-menu {
            margin-top: 2px; /* Adjust dropdown position */
            position: absolute !important;
        }
        .header {
            text-align: center;
            padding: 50px 20px;
            background-image: url('../img/background.png');
            background-size: cover;
            background-position: center;
            color: white; /* Ensure text is visible over background */
        }
        .btn-primary {
            color: #fff;
            background-color: #12229D;
            border: 2px solid #12229D;
            font-size: 16px; /* Custom font size */
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #12229D;
            border-color: #12229D;
        }
        .table-responsive {
            max-height:450px;
        }
        thead th {
        position: sticky;
        top: 0;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../img/horizontallogo.png" alt="Clinic Logo">
        </a>

        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #12229D">
                    <i class="fas fa-user-circle fa-lg" style="color: #12229D"></i> <!-- Font Awesome profile icon -->
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../Models/handleLogout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

    <div class="container mt-3">
        <div class="card">
            <div class="card-body">
                <form method="post" class="form-inline mb-3">
                    <div class="form-group mr-3">
                        <label for="dateFilter">Filter by Date:</label>
                        <input type="date" class="form-control mx-sm-2" id="dateFilter" name="dateFilter">
                    </div>
                    <div class="form-group mr-3">
                        <label for="patientName">Search by Patient Name:</label>
                        <input type="text" class="form-control mx-sm-2" id="patientName" name="patientName" placeholder="Enter patient name">
                    </div>
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                </form>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Patient Name</th>
                            <th>Reason</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row = mysqli_fetch_assoc($resultAppointments)) {
                            $date = date('F j, Y', strtotime($row['date_preference']));
                            $time = date('h:i A', strtotime($row['time_preference']));

                            $patientName = $row['patient_fname'] . ' ' . $row['patient_lname'];
                            $reason = $row['reason'];

                            echo "<tr>";
                            echo "<td>$date</td>";
                            echo "<td>$time</td>";
                            echo "<td>$patientName</td>";
                            echo "<td>$reason</td>";
                            echo '<td><a href="doctorAppointment.php?id=' . $row['appointmentID'] . '" class="btn btn-primary">Open Appointment</a></td>';
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scroll-to-top.js"></script>
</body>
</html>