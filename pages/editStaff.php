<?php
session_start();
require '../models/db.php';
// Check if user is logged in as a doctor
if (!isset($_SESSION['userID']) || $_SESSION['userType'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $staffID = $_GET['id'];
    $sql = "SELECT * FROM staff WHERE staffID = $staffID";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    $staffID = $_POST['staffID'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $staffType = $_POST['staffType'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    if (!empty($newPassword) && ($newPassword == $confirmPassword)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE staff SET fname='$fname', lname='$lname', email='$email', staffType='$staffType', password='$hashedPassword' WHERE staffID=$staffID";
    } else {
        $sql = "UPDATE staff SET fname='$fname', lname='$lname', email='$email', staffType='$staffType' WHERE staffID=$staffID";
    }

    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error updating record: " . $conn->error;
    }
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
    <div class="content">
    <div><a href="staffTable.php" class="btn btn-primary">Back</a></div>
    <div class="container mt-5">
    
    <form method="POST" action="" class="row g-3">
        <input type="hidden" name="staffID" value="<?php echo $row['staffID']; ?>">
        <div class="form-group col-md-6">
            <label for="fname">First Name</label>
            <input type="text" class="form-control" id="fname" name="fname" value="<?php echo $row['fname']; ?>" required>
        </div>
        <div class="form-group col-md-6">
            <label for="lname">Last Name</label>
            <input type="text" class="form-control" id="lname" name="lname" value="<?php echo $row['lname']; ?>" required>
        </div>
        <div class="form-group col-md-6">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required>
        </div>
        <div class="form-group col-md-2">
            <label for="staffType">Staff Type</label>
            <select class="form-control" id="staffType" name="staffType" required>
                <option value="secretary" <?php if ($row['staffType'] == 'secretary') echo 'selected'; ?>>Secretary</option>
                <option value="doctor" <?php if ($row['staffType'] == 'doctor') echo 'selected'; ?>>Doctor</option>
                <option value="admin" <?php if ($row['staffType'] == 'admin') echo 'selected'; ?>>Admin</option>
            </select>
        </div>
        <div class="form-group col-md-6">
            <label for="newPassword">New Password</label>
            <input type="password" class="form-control" id="newPassword" name="newPassword">
        </div>
        <div class="form-group col-md-6">
            <label for="confirmPassword">Confirm New Password</label>
            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
        </div>
        <button type="submit" name="update" class="btn btn-primary col-md-2">Update</button>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </div>
</body>
<script>
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