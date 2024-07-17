<?php include '../Models/checkSession.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Clinic - Dashboard</title>
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        body {
            background-color: #f0f0f0; /* Light gray background */
        }
        .header {
            text-align: center;
            padding: 50px 20px;
            background-image: url('../img/background.png');
            background-size: cover;
            background-position: center;
            color: white; /* Ensure text is visible over background */
        }
    </style>
</head>
<body>

<?php include 'navbar.html'; ?>

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
        <p>Thank you for trusting us with your healthcare needs.</p>
        <p style="font-size: 0.8rem;">Last Login: <?php echo $lastLogin; ?></p>
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
