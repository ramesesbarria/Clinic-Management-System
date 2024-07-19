<?php
session_start();
require '../models/db.php';
// Check if user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = password_hash($_POST['pasxsword'], PASSWORD_DEFAULT);
    $staffType = $conn->real_escape_string($_POST['staffType']);

    // Prepare SQL statement
    $sql = "INSERT INTO staff (fname, lname, email, password, staffType) VALUES ('$fname', '$lname', '$email', '$password', '$staffType')";

    if (mysqli_query($conn, $sql)) {
        $insertSuccess = true;
    }

    header("Location: registerStaff.php?success=" . $insertSuccess);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        .sidebar a, .dropdown-btn {
            display: block;
            padding: 10px 0;
            color: lightgray;
            text-decoration: none;
        }
        .sidebar a:hover, .dropdown-btn:hover {
            color: #f1f1f1;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin: 20px;
        }
        .dropdown-container {
  display: none;
  background-color: #1a202c;
  padding-left: 8px;
}
</style>
</head>

<body>
<div class="sidebar">
        <div class="logo"> <img src="../img/logo.png" alt="Logo"> </div>
        <b> </b>
        <h2 class="text-xl font-bold mb-6">Admin Dashboard</h2>
        <!-- <button class="dropdown-btn">Tables 
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-container">
    <a href="#">appointments</a>
    <a href="#">patient</a>
    <a href="#">patientrecord</a>
    <a href="#">prescription</a>
    <a href="#">staff</a>
  </div> -->
        <a href="registerStaff.php">Register Staff</a>
        <a href="staffTable.php">Staff Table</a>
        <a href="../models/handleLogout.php">Log Out</a>
    </div>
    <div class="container mt-5 shadow p-3 mb-5 bg-white rounded content">
        <h2 class="mb-4">Add New Staff Member</h2>
        <form method="POST" class="row g-3">
            <div class="form-group col-md-6">
                <label for="fname">First Name</label>
                <input type="text" class="form-control" id="fname" name="fname" placeholder="Enter first name" required>
            </div>
            <div class="form-group col-md-6">
                <label for="lname">Last Name</label>
                <input type="text" class="form-control" id="lname" name="lname" placeholder="Enter last name" required>
            </div>
            <div class="form-group col-md-6">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group col-md-6">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter password" required>
            </div>
            <div class="form-group col-md-3">
                <label for="staffType">Staff Type</label>
                <select class="form-control" id="staffType" name="staffType" required>
                    <option value="" disabled selected>Select staff type</option>
                    <option value="secretary">Secretary</option>
                    <option value="doctor">Doctor</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
    <!-- Toast Notification -->
<div class="toast position-fixed bottom-0 end-0 p-3" style="z-index: 11" id="successToast" data-delay="5000">
    <div class="toast-header">
        <strong class="me-auto">Success</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
        New staff member added successfully!
    </div>
</div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
<script>
    $(document).ready(function () {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === '1') {
            $('#successToast').toast('show');
            
            // Remove the success parameter from the URL
            const url = new URL(window.location);
            url.searchParams.delete('success');
            window.history.replaceState({}, document.title, url.toString());
        }
    });

/* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */
var dropdown = document.getElementsByClassName("dropdown-btn");
var i;

for (i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var dropdownContent = this.nextElementSibling;
    if (dropdownContent.style.display === "block") {
      dropdownContent.style.display = "none";
    } else {
      dropdownContent.style.display = "block";
    }
  });
}
</script>
</html>