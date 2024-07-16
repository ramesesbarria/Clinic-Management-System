<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recent Appointments</title>
    <style>
        * {
            font-family: 'Merriweather', serif;
        }
        .content-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            color: #fff;
            background-color: #12229D;
            border: 2px solid #12229D;
            font-size: 16px;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #12229D;
            border-color: #12229D;
        }
        .clickable-row:hover {
            background-color: #f0f0f0; /* Light gray background on hover */
            cursor: pointer; /* Change cursor to pointer on hover */
        }
    </style>
</head>
<body>
<section class="container content-container">
    <h2>Recent Appointments</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Appointment Type</th>
                    <th>Reason</th>
                    <th>Approved</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $user_id = $_SESSION['patientID'] ?? null;
                $appointments = array();

                if ($user_id) {
                    // Use prepared statement to prevent SQL injection
                    $sql = "SELECT * FROM appointments 
                            WHERE patientID = ? 
                            AND approved = true 
                            AND (
                                date_preference < CURDATE() 
                                OR (date_preference = CURDATE() AND time_preference < CURTIME())
                            )
                            ORDER BY date_preference DESC, time_preference DESC LIMIT 1";

                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if ($result) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $appointments[] = $row;
                            ?>
                            <!-- Modal for Appointment Details -->
                            <div class="modal fade" id="appointmentModal_<?php echo $row['appointmentID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Appointment Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Date: <?php echo htmlspecialchars($row['date_preference']); ?></p>
                                            <p>Time: <?php echo htmlspecialchars($row['time_preference']); ?></p>
                                            <p>Appointment Type: <?php echo htmlspecialchars($row['appointment_type']); ?></p>
                                            <p>Reason: <?php echo htmlspecialchars($row['reason']); ?></p>
                                            <p>Approved: <?php echo $row['approved'] ? 'Yes' : 'No'; ?></p>
                                            <!-- Additional details as needed -->
                                        </div>
                                        <div class="modal-footer">
                                            <div class="row">
                                                <div class="col text-end">
                                                    <a href="view_prescription.php?appointment_id=<?php echo $row['appointmentID']; ?>" class="btn btn-primary text-decoration-none">
                                                        <i class="fas fa-file-prescription fa-lg"></i> Prescription
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "Error executing SQL: " . mysqli_error($conn);
                    }

                    mysqli_stmt_close($stmt); // Close statement
                }

                mysqli_close($conn); // Close database connection

                // Display appointments in the table
                foreach ($appointments as $appointment):
                ?>
                    <tr class="clickable-row" data-bs-toggle="modal" data-bs-target="#appointmentModal_<?php echo $appointment['appointmentID']; ?>">
                        <td><?php echo htmlspecialchars($appointment['date_preference']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['time_preference']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['appointment_type']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                        <td><?php echo $appointment['approved'] ? 'Yes' : 'No'; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('.clickable-row').click(function() {
            var target = $(this).data('target');
            $(target).modal('show');
        });
    });
</script>
</body>
</html>
