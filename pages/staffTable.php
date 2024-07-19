<?php
session_start();
require '../models/db.php';
// Check if user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM staff";
$result = $conn->query($sql);
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
.table-responsive {
            max-height: 500px;
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
    <div class="content">
    <div class="container mt-5 table-responsive" >
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Staff Type</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['staffID']}</td>
                            <td>{$row['fname']}</td>
                            <td>{$row['lname']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['staffType']}</td>
                            <td>{$row['created_at']}</td>
                            <td>
                                <a href='editStaff.php?id={$row['staffID']}' class='btn btn-warning btn-sm'>Edit</a>
                                <a href='../models/deleteStaff.php?id={$row['staffID']}' class='btn btn-danger btn-sm' onclick='return confirmDelete()'>Delete</a>
                            </td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No records found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    </div>
</body>
<script>
function confirmDelete() {
    return confirm("Are you sure you want to delete this record?");
}
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