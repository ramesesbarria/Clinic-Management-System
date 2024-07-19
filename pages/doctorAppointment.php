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
$queryPatient = "SELECT * FROM patient WHERE patientID = $patientID";
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
    $chief_complaint = isset($_POST['chief_complaint']) ? $_POST['chief_complaint'] : '';
    $duration_severity = isset($_POST['duration_severity']) ? $_POST['duration_severity'] : '';
    $general_appearance = isset($_POST['general_appearance']) ? $_POST['general_appearance'] : '';
    $visible_signs = isset($_POST['visible_signs']) ? $_POST['visible_signs'] : '';

    $updateAppointment = "UPDATE appointments SET
                          chief_complaint = '$chief_complaint',
                          duration_severity = '$duration_severity',
                          general_appearance = '$general_appearance',
                          visible_signs = '$visible_signs'
                          WHERE appointmentID = $appointmentID";

    mysqli_query($conn, $updateAppointment);

    // Update patient record
    $medical_history = isset($_POST['medical_history']) ? $_POST['medical_history'] : '';
    $height = isset($_POST['height']) ? $_POST['height'] : '';
    $weight = isset($_POST['weight']) ? $_POST['weight'] : '';
    $blood_pressure = isset($_POST['blood_pressure']) ? $_POST['blood_pressure'] : '';
    $pulse_rate = isset($_POST['pulse_rate']) ? $_POST['pulse_rate'] : '';
    $temperature = isset($_POST['temperature']) ? $_POST['temperature'] : '';
    $respiratory_rate = isset($_POST['respiratory_rate']) ? $_POST['respiratory_rate'] : '';
    $current_medications = isset($_POST['current_medications']) ? $_POST['current_medications'] : '';
    $past_medications = isset($_POST['past_medications']) ? $_POST['past_medications'] : '';
    $allergies = isset($_POST['allergies']) ? $_POST['allergies'] : '';
    $major_past_illnesses = isset($_POST['major_past_illnesses']) ? $_POST['major_past_illnesses'] : '';

    // Update patient record
    $medical_history = isset($_POST['medical_history']) ? $_POST['medical_history'] : '';
    $height = isset($_POST['height']) ? $_POST['height'] : 'NULL'; // Use 'NULL' for SQL NULL value
    $weight = isset($_POST['weight']) ? $_POST['weight'] : 'NULL'; // Use 'NULL' for SQL NULL value
    $blood_pressure = isset($_POST['blood_pressure']) ? $_POST['blood_pressure'] : '';
    $pulse_rate = isset($_POST['pulse_rate']) ? $_POST['pulse_rate'] : 'NULL'; // Use 'NULL' for SQL NULL value
    $temperature = isset($_POST['temperature']) ? $_POST['temperature'] : 'NULL'; // Use 'NULL' for SQL NULL value
    $respiratory_rate = isset($_POST['respiratory_rate']) ? $_POST['respiratory_rate'] : 'NULL'; // Use 'NULL' for SQL NULL value
    $current_medications = isset($_POST['current_medications']) ? $_POST['current_medications'] : '';
    $past_medications = isset($_POST['past_medications']) ? $_POST['past_medications'] : '';
    $allergies = isset($_POST['allergies']) ? $_POST['allergies'] : '';
    $major_past_illnesses = isset($_POST['major_past_illnesses']) ? $_POST['major_past_illnesses'] : '';

    // Build the SQL query using mysqli_real_escape_string for string values
   // Update patient record
   $medical_history = isset($_POST['medical_history']) ? $_POST['medical_history'] : '';
   $height = isset($_POST['height']) ? floatval($_POST['height']) : 'NULL'; // Use 'NULL' for SQL NULL value
   $weight = isset($_POST['weight']) ? floatval($_POST['weight']) : 'NULL'; // Use 'NULL' for SQL NULL value
   $blood_pressure = isset($_POST['blood_pressure']) ? $_POST['blood_pressure'] : '';
   $pulse_rate = isset($_POST['pulse_rate']) ? intval($_POST['pulse_rate']) : 'NULL'; // Use 'NULL' for SQL NULL value
   $temperature = isset($_POST['temperature']) ? floatval($_POST['temperature']) : 'NULL'; // Use 'NULL' for SQL NULL value
   $respiratory_rate = isset($_POST['respiratory_rate']) ? intval($_POST['respiratory_rate']) : 'NULL'; // Use 'NULL' for SQL NULL value
   $current_medications = isset($_POST['current_medications']) ? $_POST['current_medications'] : '';
   $past_medications = isset($_POST['past_medications']) ? $_POST['past_medications'] : '';
   $allergies = isset($_POST['allergies']) ? $_POST['allergies'] : '';
   $major_past_illnesses = isset($_POST['major_past_illnesses']) ? $_POST['major_past_illnesses'] : '';

   // Build the SQL query using mysqli_real_escape_string for string values
   $updatePatientRecord = "UPDATE patientRecord SET
                           medical_history = '" . mysqli_real_escape_string($conn, $medical_history) . "',
                           height = $height,
                           weight = $weight,
                           blood_pressure = '" . mysqli_real_escape_string($conn, $blood_pressure) . "',
                           pulse_rate = $pulse_rate,
                           temperature = $temperature,
                           respiratory_rate = $respiratory_rate,
                           current_medications = '" . mysqli_real_escape_string($conn, $current_medications) . "',
                           past_medications = '" . mysqli_real_escape_string($conn, $past_medications) . "',
                           allergies = '" . mysqli_real_escape_string($conn, $allergies) . "',
                           major_past_illnesses = '" . mysqli_real_escape_string($conn, $major_past_illnesses) . "'
                           WHERE appointmentID = $appointmentID";

   mysqli_query($conn, $updatePatientRecord);

    if (isset($_POST['prescription_visible']) && $_POST['prescription_visible'] === '1') {
        // Insert new row in prescriptions table
        $prescription_text = isset($_POST['prescription_text']) ? $_POST['prescription_text'] : '';
        $doctors_notes = isset($_POST['doctors_notes']) ? $_POST['doctors_notes'] : '';
        $diagnosis = isset($_POST['diagnosis']) ? $_POST['diagnosis'] : '';

        $insertPrescription = "INSERT INTO prescription (patientID, prescription_text, doctors_notes, diagnosis) VALUES ('$patientID', '$prescription_text', '$doctors_notes', '$diagnosis')";
        mysqli_query($conn, $insertPrescription);
    }

    $archiveAppointment = "UPDATE appointments SET completed = 1 WHERE appointmentID = $appointmentID";
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        * {
            font-family: 'Merriweather', serif;
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

        .front {
            z-index: 100;
        }

        .back {
            z-index: 99;
        }
        .d-flex {
            gap:50px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
    <div class="container">
        <a class="navbar-brand" href="#">
            <img src="../img/horizontallogo.png" alt="Clinic Logo">
        </a>

        <ul class="navbar-nav ms-auto front">
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
        <div class="mb-3 sticky shadow-sm p-3 mb-5 bg-white rounded d-flex justify-content-around back" id="appointmentInfo">
    <div class="row">
            <div class="bg-white d-flex justify-content-around bg-primary" style="margin-bottom: 10px;">
        <div><a href="doctorDashboard.php" class="btn btn-primary">Back to Dashboard</a></div>
        <div><p><strong>Preferred Date:</strong> <?php echo date('F j, Y', strtotime($appointment['date_preference'])); ?></p></div>
        <div><p><strong>Preferred Time:</strong> <?php echo date('h:i A', strtotime($appointment['time_preference'])); ?></p></div>
    </div>
    <div class="bg-white d-flex justify-content-around bg-primary">
    <table class="table">
  <thead>
    <tr>
      <th scope="col">Name</th>
      <th scope="col">Date Of Birth</th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $patient['first_name'] . ' ' . $patient['last_name']; ?></td>
      <td><?php echo date('F j, Y', strtotime($patient['dob'])); ?></td>
      <td><a href="patient_history.php?patientID=<?php echo $patientID; ?>" class="btn btn-secondary">History</a></td>
    </tr>
    <tr>
  </tbody>
</table>
    </div>
    </div>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scroll-to-top.js"></script>
</body>
</html>

