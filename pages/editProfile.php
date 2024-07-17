<?php
include '../Models/checkSession.php'; // Ensure session is checked

// Initialize variables with session data or default values
$firstName = $_SESSION['user_first_name'] ?? '';
$lastName = $_SESSION['user_last_name'] ?? '';
$dob = $_SESSION['user_dob'] ?? '';
$address = $_SESSION['user_address'] ?? '';
$phoneNumber = $_SESSION['user_phone'] ?? '';
$email = $_SESSION['user_email'] ?? '';
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Clinic - Edit Profile</title>
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
       body {
            background-image: url('../img/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100%; /* Ensure full height background */
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="../Pages/landingPage.php">
                <img src="../img/horizontallogo.png" alt="Clinic Logo">
            </a>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="color: #12229D">
                        <i class="fas fa-user-circle fa-lg" style="color: #12229D"></i> <!-- Font Awesome profile icon -->
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="editProfile.php">Edit Profile</a></li>
                        <li><a class="dropdown-item" href="appointmentHistory.php">Appointment History</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../Models/handleLogout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <a href="landingPage.php" class="back-btn">
                            <i class="fas fa-arrow-left fa-lg"></i>Return to dashboard
                        </a>
                        <h3 class="card-title text-left mb-4 mt-3">Edit Your Profile</h3>
                        <!-- Form for editing profile -->
                        <form action="../Models/updateProfile.php" method="POST">
                            <!-- Input fields for editing profile -->
                            <div class="mb-3">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo htmlspecialchars($firstName); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo htmlspecialchars($lastName); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required><?php echo htmlspecialchars($address); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="phoneNumber" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo htmlspecialchars($phoneNumber); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                            </div>
                            <div class="text-end">
                                <button type="submit" class="btn btn-primary" onclick="return confirmUpdate();">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Function to confirm before submitting the form
        function confirmUpdate() {
            // Display confirmation dialog
            if (confirm('Are you sure you want to update your profile?')) {
                // If user confirms, submit the form
                document.querySelector('form').submit();
                alert('Appointment edited successfully!');
            } else {
                // If user cancels, do nothing
                location.reload();
                return false;
            }
        }
    </script>
</body>
</html>
