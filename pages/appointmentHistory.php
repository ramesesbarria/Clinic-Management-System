<?php
include '../Models/db.php'; // Include database configuration
include '../Models/checkSession.php'; // Include session check script

// Pagination settings
$results_per_page = 10;
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Fetch appointments for the logged-in user
$user_id = $_SESSION['patientID'] ?? null;
$appointments = array();

if ($user_id) {
    // Count total records
    $sql_count = "SELECT COUNT(*) AS total FROM appointments WHERE patientID = ?";
    $stmt_count = mysqli_prepare($conn, $sql_count);
    mysqli_stmt_bind_param($stmt_count, "i", $user_id);
    mysqli_stmt_execute($stmt_count);
    $result_count = mysqli_stmt_get_result($stmt_count);
    $row_count = mysqli_fetch_assoc($result_count);
    $total_records = $row_count['total'];

    // Calculate number of pages
    $total_pages = ceil($total_records / $results_per_page);

    // Calculate SQL LIMIT clause
    $start_limit = ($current_page - 1) * $results_per_page;

    // Retrieve actual data with pagination
    $sql = "SELECT * FROM appointments WHERE patientID = ? ORDER BY date_preference DESC, time_preference DESC LIMIT ?, ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iii", $user_id, $start_limit, $results_per_page);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
    } else {
        echo "Error executing SQL: " . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt); // Close statement
}

mysqli_close($conn); // Close database connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Clinic - Appointment History</title>
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
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
        .content-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .btn-danger, .btn-success {
            font-size: 0.8rem;
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

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <section class="content-container mb-3">
                    <!-- Back button and title -->
                    <a href="landingPage.php" class="back-btn">
                        <i class="fas fa-arrow-left fa-lg"></i>Return to dashboard
                    </a>
                    <h3 class="mt-3">Appointment History</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($appointments as $appointment):
                                    $appointmentDateTime = strtotime($appointment['date_preference'] . ' ' . $appointment['time_preference']);
                                    $currentDateTime = time();
                                    $editable = ($appointmentDateTime - $currentDateTime) > (24 * 3600); // 24 hours in seconds
                                    $isPastAppointment = ($appointmentDateTime < $currentDateTime);
                                ?>
                                    <tr class="clickable-row" data-bs-toggle="modal" data-bs-target="#appointmentModal_<?php echo $appointment['appointmentID']; ?>">
                                        <td><?php echo htmlspecialchars($appointment['date_preference']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['time_preference']); ?></td>
                                        <td><?php echo htmlspecialchars($appointment['appointment_type']); ?></td>
                                        <td class="<?php echo getStatusClass($appointment); ?>">
                                            <?php echo getStatusText($appointment); ?>
                                        </td>
                                    </tr>

                                    <!-- Modal for Appointment Details -->
                                    <div class="modal fade" id="appointmentModal_<?php echo $appointment['appointmentID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Appointment Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div id="detailsSection_<?php echo $appointment['appointmentID']; ?>">
                                                        <p><span class="label-date">Date:</span> <?php echo htmlspecialchars($appointment['date_preference']); ?></p>
                                                        <p><span class="label-time">Time:</span> <?php echo htmlspecialchars($appointment['time_preference']); ?></p>
                                                        <p><span class="label-type">Type:</span> <?php echo htmlspecialchars($appointment['appointment_type']); ?></p>
                                                        <p><span class="label-reason">Reason:</span> <?php echo htmlspecialchars($appointment['reason']); ?></p>
                                                        <p><span class="label-status">Status:</span> <span class="<?php echo getStatusClass($appointment); ?>">
                                                            <?php echo getStatusText($appointment); ?>
                                                        </span></p>
                                                    </div>
                                                    <div id="editSection_<?php echo $appointment['appointmentID']; ?>" style="display: none;">
                                                        <!-- Include the form for editing appointment -->
                                                        <form id="editAppointmentForm_<?php echo $appointment['appointmentID']; ?>" action="../models/updateAppPatientSide.php" method="POST">
                                                            <input type="hidden" name="appointmentID" value="<?php echo $appointment['appointmentID']; ?>">
                                                            <div class="mb-3">
                                                                <label for="editDate_<?php echo $appointment['appointmentID']; ?>" class="form-label">Date</label>
                                                                <input type="date" class="form-control" id="editDate_<?php echo $appointment['appointmentID']; ?>" name="date_preference" value="<?php echo htmlspecialchars($appointment['date_preference']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editTime_<?php echo $appointment['appointmentID']; ?>" class="form-label">Time</label>
                                                                <input type="time" class="form-control" id="editTime_<?php echo $appointment['appointmentID']; ?>" name="time_preference" value="<?php echo htmlspecialchars($appointment['time_preference']); ?>" required>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editAppointmentType_<?php echo $appointment['appointmentID']; ?>" class="form-label">Type</label>
                                                                <select class="form-select" id="editAppointmentType_<?php echo $appointment['appointmentID']; ?>" name="appointment_type" required>
                                                                    <option value="Regular Checkup" <?php echo ($appointment['appointment_type'] == 'Regular Checkup') ? 'selected' : ''; ?>>Regular Checkup</option>
                                                                    <option value="Specific Treatment" <?php echo ($appointment['appointment_type'] == 'Specific Treatment') ? 'selected' : ''; ?>>Specific Treatment</option>
                                                                    <option value="Consultation" <?php echo ($appointment['appointment_type'] == 'Consultation') ? 'selected' : ''; ?>>Consultation</option>
                                                                </select>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label for="editReason_<?php echo $appointment['appointmentID']; ?>" class="form-label">Reason</label>
                                                                <textarea class="form-control" id="editReason_<?php echo $appointment['appointmentID']; ?>" name="reason" rows="3" required><?php echo htmlspecialchars($appointment['reason']); ?></textarea>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="row">
                                                        <div class="col text-end">
                                                            <?php if ($appointment['approved'] == 1 && $appointment['completed'] == 1 && $appointment['archived'] == 1 && !$editable): ?>
                                                                <a href="view_prescription.php?appointment_id=<?php echo $appointment['appointmentID']; ?>" class="btn btn-primary text-decoration-none">
                                                                    <i class="fas fa-file-prescription fa-lg"></i> Prescription
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="row text-end">
                                                            <?php if ($editable): ?>
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="button" class="btn btn-primary me-2" onclick="toggleEdit('<?php echo $appointment['appointmentID']; ?>')" id="editBtn_<?php echo $appointment['appointmentID']; ?>">
                                                                        <i class="fas fa-edit fa-lg"></i> Edit
                                                                    </button>
                                                                    <button type="button" class="btn btn-danger" onclick="cancelAppointment('<?php echo $appointment['appointmentID']; ?>')" id="cancelBtn_<?php echo $appointment['appointmentID']; ?>">
                                                                        <i class="fas fa-trash-alt fa-lg"></i> Cancel
                                                                    </button>
                                                                    <button type="button" class="btn btn-success" style="display: none;" onclick="submitForm('<?php echo $appointment['appointmentID']; ?>')" id="saveChangesBtn_<?php echo $appointment['appointmentID']; ?>">
                                                                        <i class="fas fa-save fa-lg"></i> Save Changes
                                                                    </button>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
                                    <script>
                                        function toggleEdit(appointmentID) {
                                            // Hide details section
                                            $('#detailsSection_' + appointmentID).hide();
                                            // Show edit section
                                            $('#editSection_' + appointmentID).show();
                                            // Change button text and onclick function
                                            $('#saveChangesBtn_' + appointmentID).show();
                                            $('#editBtn_' + appointmentID).hide();
                                            $('#cancelBtn_' + appointmentID).hide();
                                        }

                                        function submitForm(appointmentID) {
                                            // Show confirmation dialog
                                            if (confirm('Are you sure you want to save these changes?')) {
                                                // If user confirms, submit the form
                                                document.getElementById('editAppointmentForm_' + appointmentID).submit();
                                                alert('Appointment edited successfully!');
                                            } else {
                                                // If user cancels, do nothing
                                                location.reload();
                                                return false;
                                            }
                                        }

                                        function cancelAppointment(appointmentID) {
                                            if (confirm('Are you sure you want to cancel this appointment?')) {
                                                $.post('../models/cancelAppointment.php', { appointmentID: appointmentID }, function(data) {
                                                    // Optional: Handle response from cancel appointment script
                                                    alert('Appointment canceled successfully!');
                                                    // Reload the page or update the UI as needed
                                                    location.reload();
                                                });
                                            }
                                        }
                                    </script>
                                <?php endforeach; ?>
                                <?php
                                // Function to determine the status class based on appointment details
                                function getStatusClass($appointment) {
                                    if ($appointment['approved'] == 1 && $appointment['completed'] == 1 && $appointment['archived'] == 1) {
                                        return 'status-complete';
                                    } elseif ($appointment['approved'] == 1 && $appointment['completed'] == 1 && $appointment['archived'] == 0) {
                                        return 'status-pending';
                                    } elseif ($appointment['approved'] == 1 && $appointment['completed'] == 0 && $appointment['archived'] == 0) {
                                        return 'status-complete';
                                    } else {
                                        return 'status-approval';
                                    }
                                }

                                // Function to determine the status text based on appointment details
                                function getStatusText($appointment) {
                                    if ($appointment['approved'] == 1 && $appointment['completed'] == 1 && $appointment['archived'] == 1) {
                                        return 'Complete';
                                    } elseif ($appointment['approved'] == 1 && $appointment['completed'] == 1 && $appointment['archived'] == 0) {
                                        return 'Pending Payment';
                                    } elseif ($appointment['approved'] == 1 && $appointment['completed'] == 0 && $appointment['archived'] == 0) {
                                        return 'Approved';
                                    } else {
                                        return 'Pending Approval';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <nav aria-label="Page navigation example">
                            <ul class="pagination justify-content-center">
                                <?php if ($current_page > 1): ?>
                                    <li class="page-item"><a class="page-link" href="?page=1">&laquo;</a></li>
                                <?php endif; ?>
                                <?php
                                $start = max(1, $current_page - 2);
                                $end = min($total_pages, $current_page + 2);

                                for ($i = $start; $i <= $end; $i++) {
                                    $active = ($current_page == $i) ? 'active' : '';
                                    echo '<li class="page-item ' . $active . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
                                }
                                ?>
                                <?php if ($current_page < $total_pages): ?>
                                    <li class="page-item"><a class="page-link" href="?page=<?php echo $total_pages; ?>">&raquo;</a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </section>
            </div>
        </div>
    </div>


</body>
</html>
