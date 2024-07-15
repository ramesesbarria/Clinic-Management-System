<?php
include '../Models/db.php'; // Include configuration
include '../Models/check_session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        * {
            font-family: 'Merriweather', serif;
        }
        body {
            background-image: url('../img/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100%; /* Ensure full height background */
        }
        .content-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
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
        .back-btn {
            color: #6e6e6e; /* Set the color of the icon */
            text-decoration: none;
            font-weight: 500;
            font-size: 0.8rem;
        }
        .back-btn:hover {
            color: #929292; /* Hover color */
        }
        .back-btn i {
            margin-right: 5px;
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
                        <li><a class="dropdown-item" href="../Models/logout.php">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>


    <div class="container-fluid"> <!-- Use container-fluid to make full width -->
        <div class="row justify-content-center"> <!-- Center content horizontally -->
            <div class="col-lg-8"> <!-- Adjust column size as needed -->
                <section class="content-container mb-3">
                    <a href="landing_page.php" class="back-btn">
                        <i class="fas fa-arrow-left fa-lg"></i>Return to dashboard
                    </a>
                    <h2 class="mt-3">Appointments</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Appointment Type</th>
                                    <th>Reason</th>
                                    <th>Approved</th>
                                    <th>Prescription</th> <!-- For media breakpoints -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Fetch appointments for the logged-in user
                                $user_id = $_SESSION['user_id'] ?? null;
                                $appointments = array();

                                if ($user_id) {
                                    $sql = "SELECT * FROM appointments WHERE patientID = $user_id ORDER BY date_preference DESC, time_preference DESC";
                                    $result = mysqli_query($conn, $sql);

                                    if (mysqli_num_rows($result) > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $appointments[] = $row;
                                        }
                                    }
                                }

                                // Display appointments in the table
                                foreach ($appointments as $appointment):
                                ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($appointment['date_preference']); ?> <?php echo htmlspecialchars($appointment['time_preference']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_type']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                                        <td><?php echo $appointment['approved'] ? 'Yes' : 'No'; ?></td>
                                        <td>
                                            <a href="view_prescription.php?appointment_id=<?php echo $appointment['patientID']; ?>" class="text-decoration-none">
                                                <i class="fas fa-file-prescription fa-lg" style="color: #12229D;"></i> <!-- Font Awesome prescription icon -->
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <a href="#" id="btnScrollToTop" class="btn-scroll-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/scroll-to-top.js"></script>
</body>
</html>
