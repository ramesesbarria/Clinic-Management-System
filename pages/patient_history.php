<?php
session_start();
require '../models/db.php';

// Check if user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'doctor') {
    header("Location: login.html");
    exit();
}

// Get patient ID from query string
$patientID = $_GET['patientID'];

// Fetch patient details
$queryPatient = "SELECT * FROM patient WHERE patientID = $patientID";
$resultPatient = mysqli_query($conn, $queryPatient);
$patient = mysqli_fetch_assoc($resultPatient);

// Initialize variables for date filters
$dateFrom = '';
$dateTo = '';

// Process date filter form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $dateFrom = $_POST['dateFrom'];
    $dateTo = $_POST['dateTo'];
}

// Fetch patient's archived appointments initially
$queryArchivedAppointments = "SELECT * FROM appointments WHERE patientID = $patientID AND archived = 1";
$resultArchivedAppointments = mysqli_query($conn, $queryArchivedAppointments);

// Fetch patient records initially
$queryPatientRecords = "SELECT * FROM patientRecord WHERE patientID = $patientID";
$resultPatientRecords = mysqli_query($conn, $queryPatientRecords);

// Apply date filters if submitted
if (!empty($dateFrom) && !empty($dateTo)) {
    // Construct conditions for date filters
    $dateFilterConditions = " AND date_preference BETWEEN '$dateFrom' AND '$dateTo'";
    
    // Update queries with date filters
    $queryArchivedAppointments .= $dateFilterConditions;
    $queryPatientRecords .= $dateFilterConditions;
    
    // Re-query with date filters
    $resultArchivedAppointments = mysqli_query($conn, $queryArchivedAppointments);
    $resultPatientRecords = mysqli_query($conn, $queryPatientRecords);
}

// Fetch doctor's first name
$doctorId = $_SESSION['userID'];
$queryDoctor = "SELECT fname FROM staff WHERE staffID = $doctorId";
$resultDoctor = mysqli_query($conn, $queryDoctor);
$rowDoctor = mysqli_fetch_assoc($resultDoctor);
$doctorFirstName = $rowDoctor['fname'];

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
    <title>Patient History</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>
        .navbar {
            justify-content: space-between;
        }
        .container-fluid {
            margin-top: 20px;
        }
        .table-container {
            margin-bottom: 20px;
        }
        .element {
  max-width: fit-content;
  margin-left: auto;
  margin-right: auto;
}
body {
            background-image: url('../img/background.png'); /* Replace with your actual path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand">Doctor Dashboard</a>
        <p class="mr-3 mt-2">Welcome, <?php echo $doctorFirstName; ?></p>
        <form method="post" class="form-inline">
            <button type="submit" class="btn btn-outline-danger my-2 my-sm-0" name="logout">Logout</button>
        </form>
    </nav>
    <div class="container mt-3">
        <a href="doctorDashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        <a href="edit.php?id=<?php echo $_SESSION['appointmentID'];?>" class="btn btn-secondary">Back to Appointment</a>
    </div>

    <!-- Main Content Section -->
    <div class="container-fluid">
            <!-- Date Filter Form -->
    <div class="container element mt-3 shadow p-3 mb-5 bg-white rounded">
        <form method="post" class="form-inline mb-3">
            <div class="form-group mr-3">
                <label for="dateFrom">From:</label>
                <input type="date" class="form-control mx-sm-3" id="dateFrom" name="dateFrom" value="<?php echo $dateFrom; ?>">
            </div>
            <div class="form-group">
                <label for="dateTo">To:</label>
                <input type="date" class="form-control mx-sm-3" id="dateTo" name="dateTo" value="<?php echo $dateTo; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Apply Filter</button>
        </form>
    </div>
    <div class="container mt-3 shadow p-3 mb-5 bg-white rounded">
        <div class="row">
            <!-- Patient Records Table -->
            <div class="col-md-6">
                <div class="table-container">
                    <h3>Patient Records</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($patientRecord = mysqli_fetch_assoc($resultPatientRecords)): ?>
                                <tr>
                                    <td><?php echo date('F j, Y', strtotime($patientRecord['updated_at'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#recordModal<?php echo $patientRecord['patientRecordID']; ?>">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($resultPatientRecords) == 0): ?>
                                <tr>
                                    <td colspan="2">None</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Archived Appointments Table -->
            <div class="col-md-6">
                <div class="table-container">
                    <h3>Archived Appointments</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($appointment = mysqli_fetch_assoc($resultArchivedAppointments)): ?>
                                <tr>
                                    <td><?php echo date('F j, Y', strtotime($appointment['date_preference'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($appointment['time_preference'])); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#appointmentModal<?php echo $appointment['appointmentID']; ?>">
                                            View Details
                                        </button>
                                    </td>
                                </tr>

                                <!-- Modal for Archived Appointment -->
                                <div class="modal fade" id="appointmentModal<?php echo $appointment['appointmentID']; ?>" tabindex="-1" role="dialog" aria-labelledby="appointmentModalLabel<?php echo $appointment['appointmentID']; ?>" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="appointmentModalLabel<?php echo $appointment['appointmentID']; ?>">Archived Appointment Details</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($appointment['date_preference'])); ?></p>
                                                <p><strong>Time:</strong> <?php echo date('h:i A', strtotime($appointment['time_preference'])); ?></p>
                                                <p><strong>Chief Complaint:</strong> <?php echo $appointment['chief_complaint']; ?></p>
                                                <p><strong>Duration & Severity:</strong> <?php echo $appointment['duration_severity']; ?></p>
                                                <p><strong>General Appearance:</strong> <?php echo $appointment['general_appearance']; ?></p>
                                                <p><strong>Visible Signs:</strong> <?php echo $appointment['visible_signs']; ?></p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php if (mysqli_num_rows($resultArchivedAppointments) == 0): ?>
                                <tr>
                                    <td colspan="7">None</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Modals for Patient Records -->
    <?php mysqli_data_seek($resultPatientRecords, 0); // Reset result set pointer ?>
    <?php while ($patientRecord = mysqli_fetch_assoc($resultPatientRecords)): ?>
        <div class="modal fade" id="recordModal<?php echo $patientRecord['patientRecordID']; ?>" tabindex="-1" role="dialog" aria-labelledby="recordModalLabel<?php echo $patientRecord['patientRecordID']; ?>" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="recordModalLabel<?php echo $patientRecord['patientRecordID']; ?>">Patient Record Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Medical History:</strong> <?php echo $patientRecord['medical_history']; ?></p>
                        <p><strong>Height:</strong> <?php echo $patientRecord['height']; ?> cm</p>
                        <p><strong>Weight:</strong> <?php echo $patientRecord['weight']; ?> kg</p>
                        <p><strong>Blood Pressure:</strong> <?php echo $patientRecord['blood_pressure']; ?></p>
                        <p><strong>Pulse Rate:</strong> <?php echo $patientRecord['pulse_rate']; ?> bpm</p>
                        <p><strong>Temperature:</strong> <?php echo $patientRecord['temperature']; ?> °C</p>
                        <p><strong>Respiratory Rate:</strong> <?php echo $patientRecord['respiratory_rate']; ?> breaths/min</p>
                        <p><strong>Current Medications:</strong> <?php echo $patientRecord['current_medications']; ?></p>
                        <p><strong>Past Medications:</strong> <?php echo $patientRecord['past_medications']; ?></p>
                        <p><strong>Allergies:</strong> <?php echo $patientRecord['allergies']; ?></p>
                        <p><strong>Major Past Illnesses:</strong> <?php echo $patientRecord['major_past_illnesses']; ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile; ?>

</body>
</html>

