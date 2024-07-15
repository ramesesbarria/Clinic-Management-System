<?php
session_start();
require '../models/db.php';

// Check if user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'doctor') {
    header("Location: login.html");
    exit();
}
$test = 0;
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

$_SESSION['appointmentID'] = $appointment['appointmentID'];
// Handle form submission to update appointment and patient record
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['logout'])) {
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

    if ($_POST['prescription_visible'] === '1') {
        // Insert new row in prescriptions table
        $prescription_text = $_POST['prescription_text'];
        $doctors_notes = $_POST['doctors_notes'];
        $diagnosis = $_POST['diagnosis'];
        
        $insertPrescription = "INSERT INTO prescription (patientID, prescription_text, doctors_notes, diagnosis) VALUES ('$patientID', '$prescription_text', '$doctors_notes', '$diagnosis')";
        mysqli_query($conn, $insertPrescription);
    }

    $archiveAppointment = "UPDATE appointments SET completed = 1, approved = 0 WHERE appointmentID = $appointmentID";
    mysqli_query($conn, $archiveAppointment);

    // Redirect to doctor dashboard
    header("Location: doctorDashboard.php");
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
        body {
            background-image: url('../img/background.png'); /* Replace with your actual path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .navbar {
            justify-content: space-between;
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section h3 {
            margin-bottom: 10px;
        }
        .header {
  padding: 10px 16px;
  background: #555;
  color: #f1f1f1;
}
.sticky {
            position: -webkit-sticky; /* For Safari */
            position: sticky;
            top: 0;
            background-color: #f8f9fa; /* Background color to match the page */
            z-index: 1000; /* Ensure it stays above other content */
            padding: 10px 0; /* Add some padding for better appearance */
        }
        .hidden {
            display: none;
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
        <div class="mb-3 sticky shadow-sm p-3 mb-5 bg-white rounded d-flex justify-content-around" id="appointmentInfo">
        <div><a href="doctorDashboard.php" class="btn btn-secondary">Back to Dashboard</a></div>
        <div><p><strong>Patient Name:</strong> <a href="patient_history.php?patientID=<?php echo $patientID; ?>"><?php echo $patient['first_name'] . ' ' . $patient['last_name']; ?></a></p></div>
        <div><p><strong>Preferred Date:</strong> <?php echo date('F j, Y', strtotime($appointment['date_preference'])); ?></p></div>
        <div><p><strong>Preferred Time:</strong> <?php echo date('h:i A', strtotime($appointment['time_preference'])); ?></p></div>
        </div>
        <h2 class="shadow-none p-3 mb-5 bg-light rounded">Update Appointment</h2>
        <form method="post" id="updateForm">
        <input type="hidden" id="prescription_visible" name="prescription_visible" value="0">
            <div class="row shadow-lg p-3 mb-5 bg-white rounded">
                <div class="col-md-6 form-section">
                    <h3>Appointment Details</h3>
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
                    <div style="margin-bottom: 5px;">
                    <button type="button" class="btn btn-primary" id="btnUpdate">Finish Appointment</button>
                    <button type="button" class="btn btn-primary" id="dropPrscptn">Create Prescription</button>
                    </div>
                    <div id="prescriptionSection" class="hidden">
                        <div class="form-group">
                            <label for="prescription_text">Prescription</label>
                            <textarea class="form-control" id="prescription_text" name="prescription_text">none</textarea>
                        </div>
                        <div class="form-group">
                            <label for="doctors_notes">Notes</label>
                            <textarea class="form-control" id="doctors_notes" name="doctors_notes">none</textarea>
                        </div>
                        <div class="form-group">
                            <label for="diagnosis">Diagnosis</label>
                            <textarea class="form-control" id="diagnosis" name="diagnosis">none</textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 form-section">
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
                </div>
            </div>
        </form>
    </div>

    <script>
        // Confirmation popup for update
        document.getElementById('btnUpdate').addEventListener('click', function() {
            if (confirm('Are you sure you want to update this information?')) {
                document.getElementById('updateForm').submit();
            }
        });

        document.getElementById('dropPrscptn').addEventListener('click', function() {
            var prescriptionSection = document.getElementById('prescriptionSection');
            var prescriptionVisibleInput = document.getElementById('prescription_visible');

            if (prescriptionSection.classList.contains('hidden')) {
                prescriptionSection.classList.remove('hidden');
                prescriptionVisibleInput.value = '1';
            } else {
                prescriptionSection.classList.add('hidden');
                prescriptionVisibleInput.value = '0';
            }
        });
    </script>

</body>
</html>

