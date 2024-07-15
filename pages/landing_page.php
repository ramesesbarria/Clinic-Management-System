<?php include '../Models/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Landing Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        * {
            font-family: 'Merriweather', serif;
        }
        body {
            background-color: #f0f0f0; /* Light gray background */
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
                    <li><a class="dropdown-item" href="edit_profile.php">Edit Profile</a></li>
                    <li><a class="dropdown-item" href="appointment_history.php">Appointment History</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../Models/handleLogout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<header class="header">
    <div class="container">
        <?php
        date_default_timezone_set('Asia/Manila');
        $hour = date('G');
        $greeting = '';
        if ($hour >= 5 && $hour < 12) {
            $greeting = 'Good morning';
        } elseif ($hour >= 12 && $hour < 18) {
            $greeting = 'Good afternoon';
        } else {
            $greeting = 'Good evening';
        }

        $firstName = isset($_SESSION['user_first_name']) ? $_SESSION['user_first_name'] : 'Guest';
        $lastLogin = isset($_SESSION['last_login']) ? date('Y-m-d h:i A', $_SESSION['last_login']) : 'Unknown';
        ?>
        <h3><?php echo "{$greeting}, {$firstName}"; ?></h3>
        <p>Welcome to Our Clinic. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
        <p>Last Login: <?php echo $lastLogin; ?></p>
        <a href="appointmentForm.html" class="btn btn-primary">Book an Appointment</a>
    </div>
</header>

<!-- Recent Appointments Section -->
<?php include '../Models/recentAppointments.php'; ?>

<!-- Upcoming Appointments Section -->
<?php include '../Models/upcomingAppointments.php'; ?>

<a href="#" id="btnScrollToTop" class="btn-scroll-top">
    <i class="fas fa-arrow-up"></i>
</a>

<!-- Footer Section -->
<?php include '../components/footer.php'; ?>

<!-- Bootstrap JavaScript Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scroll-to-top.js"></script>
</body>
</html>
