<?php
include '../Models/check_session.php'; // Ensure session is checked

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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Merriweather', serif;
        }
        /* Custom CSS */
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
            position: absolute;
        }
        .header {
            text-align: center;
            padding: 50px 20px;
            background-image: url('../img/background.png');
            background-size: cover;
            background-position: center;
            color: white; /* Ensure text is visible over background */
        }
        .steps { padding: 20px 0; }
        .step { padding: 20px; text-align: center; }
        .step h5 { margin-top: 10px; } 
        .footer { 
            background-color: #12229D; /* Blue background color */
            padding: 20px; 
            text-align: center; 
            color: white; /* White text color */
        }

        /* Adjusted primary button style */
        .btn-primary {
            color: #fff;
            background-color: #12229D;
            border: 2px solid #12229D;
            padding: 10px 20px; /* Custom padding */
            font-size: 16px; /* Custom font size */
            transition: background-color 0.3s, border-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #12229D;
            border-color: #12229D;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="landing_page.php">
                <img src="../img/horizontallogo.png" alt="Clinic Logo">
            </a>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle fa-lg"></i> <!-- Font Awesome profile icon -->
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                        <li><a class="dropdown-item" href="#">Appointment History</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../Models/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h3 class="card-title text-center mb-4">Edit Your Profile</h3>
                        <!-- Form for editing profile -->
                        <form action="../Models/update_profile.php" method="POST">
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
                            <button type="submit" class="btn btn-primary">Update Profile</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../Models/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
