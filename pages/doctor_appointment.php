<?php
session_start();
require '../models/db.php';
// Check if user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'doctor') {
    header("Location: login.html");
    exit();
}
// Fetch doctor's first name
$doctorId = $_SESSION['userID'];
$queryDoctor = "SELECT fname FROM staff WHERE staffID = $doctorId";
$resultDoctor = mysqli_query($conn, $queryDoctor);
$rowDoctor = mysqli_fetch_assoc($resultDoctor);
$doctorFirstName = $rowDoctor['fname'];

// Get appointment ID from query string
$appointmentID = $_GET['id'];

// Fetch appointment details
$queryAppointment = "SELECT * FROM appointments WHERE appointmentID = $appointmentID";
$resultAppointment = mysqli_query($conn, $queryAppointment);
$appointment = mysqli_fetch_assoc($resultAppointment);

// Fetch patient details
$patientID = $appointment['patientID'];
$queryPatient = "SELECT first_name, last_name FROM patient WHERE patientID = $patientID";
$resultPatient = mysqli_query($conn, $queryPatient);
$patient = mysqli_fetch_assoc($resultPatient);

// Fetch patient record
$queryPatientRecord = "SELECT * FROM patientRecord WHERE appointmentID = $appointmentID";
$resultPatientRecord = mysqli_query($conn, $queryPatientRecord);
$patientRecord = mysqli_fetch_assoc($resultPatientRecord);

// Handle form submission to update appointment and patient record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    // Update appointment
    $chief_complaint = $_POST['chief_complaint'];
    $duration_severity = $_POST['duration_severity'];
    $general_appearance = $_POST['general_appearance'];
    $visible_signs = $_POST['visible_signs'];

    $updateAppointment = "UPDATE appointments SET 
                          chief_complaint = '$chief_complaint',
                          duration_severity = '$duration_severity',
                          general_appearance = '$general_appearance',
                          visible_signs = '$visible_signs'
                          WHERE appointmentID = $appointmentID";

    mysqli_query($conn, $updateAppointment);

    // Update patient record
    $medical_history = $_POST['medical_history'];
    $height = $_POST['height'];
    $weight = $_POST['weight'];
    $blood_pressure = $_POST['blood_pressure'];
    $pulse_rate = $_POST['pulse_rate'];
    $temperature = $_POST['temperature'];
    $respiratory_rate = $_POST['respiratory_rate'];
    $current_medications = $_POST['current_medications'];
    $past_medications = $_POST['past_medications'];
    $allergies = $_POST['allergies'];
    $major_past_illnesses = $_POST['major_past_illnesses'];

    $updatePatientRecord = "UPDATE patientRecord SET 
                            medical_history = '$medical_history',
                            height = $height,
                            weight = $weight,
                            blood_pressure = '$blood_pressure',
                            pulse_rate = $pulse_rate,
                            temperature = $temperature,
                            respiratory_rate = $respiratory_rate,
                            current_medications = '$current_medications',
                            past_medications = '$past_medications',
                            allergies = '$allergies',
                            major_past_illnesses = '$major_past_illnesses'
                            WHERE appointmentID = $appointmentID";

    mysqli_query($conn, $updatePatientRecord);

    $archiveAppointment = "UPDATE appointments SET archived = 1, approved = 0 WHERE appointmentID = $appointmentID";
    mysqli_query($conn, $archiveAppointment);

    

    // Redirect to doctor dashboard
    header("Location: doctor_dashboard.php");
    exit();
}

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
    <title>Update Appointment</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .navbar {
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-light bg-light">
        <a class="navbar-brand">Doctor Dashboard</a>
        <p class="mr-3 mt-2">Welcome, <?php echo $doctorFirstName; ?></p>
        <form method="post" class="form-inline">
            <a href="patientlist.php" class="nav-item nav-link">Patients</a>
            <button type="submit" class="btn btn-outline-danger my-2 my-sm-0" name="logout">Logout</button>
        </form>
    </nav>
    <div class="container mt-3">
        <a href="doctor_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
    <div class="container mt-3">
        <h2>Update Appointment</h2>
        <div class="mb-3">
            <p><strong>Patient Name:</strong> <?php echo $patient['first_name'] . ' ' . $patient['last_name']; ?></p>
            <p><strong>Preferred Date:</strong> <?php echo date('F j, Y', strtotime($appointment['date_preference'])); ?></p>
            <p><strong>Preferred Time:</strong> <?php echo date('h:i A', strtotime($appointment['time_preference'])); ?></p>
        </div>
        <form method="post">
            <div class="form-group">
                <label for="chief_complaint">Chief Complaint</label>
                <textarea class="form-control" id="chief_complaint" name="chief_complaint"><?php echo $appointment['chief_complaint']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="duration_severity">Duration & Severity</label>
                <textarea class="form-control" id="duration_severity" name="duration_severity"><?php echo $appointment['duration_severity']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="general_appearance">General Appearance</label>
                <textarea class="form-control" id="general_appearance" name="general_appearance"><?php echo $appointment['general_appearance']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="visible_signs">Visible Signs</label>
                <textarea class="form-control" id="visible_signs" name="visible_signs"><?php echo $appointment['visible_signs']; ?></textarea>
            </div>
            <h3>Patient Record</h3>
            <div class="form-group">
                <label for="medical_history">Medical History</label>
                <textarea class="form-control" id="medical_history" name="medical_history"><?php echo $patientRecord['medical_history']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="height">Height</label>
                <input type="number" step="0.01" class="form-control" id="height" name="height" value="<?php echo $patientRecord['height']; ?>">
            </div>
            <div class="form-group">
                <label for="weight">Weight</label>
                <input type="number" step="0.01" class="form-control" id="weight" name="weight" value="<?php echo $patientRecord['weight']; ?>">
            </div>
            <div class="form-group">
                <label for="blood_pressure">Blood Pressure</label>
                <input type="text" class="form-control" id="blood_pressure" name="blood_pressure" value="<?php echo $patientRecord['blood_pressure']; ?>">
            </div>
            <div class="form-group">
                <label for="pulse_rate">Pulse Rate</label>
                <input type="number" class="form-control" id="pulse_rate" name="pulse_rate" value="<?php echo $patientRecord['pulse_rate']; ?>">
            </div>
            <div class="form-group">
                <label for="temperature">Temperature</label>
                <input type="number" step="0.1" class="form-control" id="temperature" name="temperature" value="<?php echo $patientRecord['temperature']; ?>">
            </div>
            <div class="form-group">
                <label for="respiratory_rate">Respiratory Rate</label>
                <input type="number" class="form-control" id="respiratory_rate" name="respiratory_rate" value="<?php echo $patientRecord['respiratory_rate']; ?>">
            </div>
            <div class="form-group">
                <label for="current_medications">Current Medications</label>
                <textarea class="form-control" id="current_medications" name="current_medications"><?php echo $patientRecord['current_medications']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="past_medications">Past Medications</label>
                <textarea class="form-control" id="past_medications" name="past_medications"><?php echo $patientRecord['past_medications']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="allergies">Allergies</label>
                <textarea class="form-control" id="allergies" name="allergies"><?php echo $patientRecord['allergies']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="major_past_illnesses">Major Past Illnesses</label>
                <textarea class="form-control" id="major_past_illnesses" name="major_past_illnesses"><?php echo $patientRecord['major_past_illnesses']; ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary" name="update">Update</button>
        </form>
    </div>
</body>
</html>
