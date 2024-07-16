<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upcoming Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            font-size: 0.8rem;
            transition: background-color 0.3s, border-color 0.3s;
        }
        .btn-primary:hover {
            background-color: #12229D;
            border-color: #12229D;
        }
        .btn-danger, .btn-success {
            font-size: 0.8rem;
        }
        .clickable-row:hover {
            background-color: #f0f0f0; /* Light gray background on hover */
            cursor: pointer; /* Change cursor to pointer on hover */
        }
    </style>
</head>
<body>
    <section class="container content-container mb-4">
        <h2>Upcoming Appointments</h2>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Appointment Type</th>
                        <th>Approved</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Fetch appointments for the logged-in user
                        $user_id = $_SESSION['patientID'] ?? null;
                        $appointments = array();

                        if ($user_id) {
                            $sql = "SELECT * 
                                    FROM appointments 
                                    WHERE patientID = ? 
                                    AND (date_preference > CURDATE() 
                                        OR (date_preference = CURDATE() AND time_preference > CURTIME()))
                                    ORDER BY date_preference ASC, time_preference ASC";

                            // Prepare the statement
                            $stmt = mysqli_prepare($conn, $sql);

                            // Bind the parameter
                            mysqli_stmt_bind_param($stmt, "i", $user_id); // Assuming patientID is an integer

                            // Execute the query
                            mysqli_stmt_execute($stmt);

                            // Get result
                            $result = mysqli_stmt_get_result($stmt);

                            // Fetch data into appointments array
                            while ($row = mysqli_fetch_assoc($result)) {
                                $appointments[] = $row;
                            }

                            // Close statement
                            mysqli_stmt_close($stmt);
                        }

                        // Display appointments in the table
                        foreach ($appointments as $appointment):
                            // Calculate if appointment is more than 24 hours away
                            $appointmentDateTime = strtotime($appointment['date_preference'] . ' ' . $appointment['time_preference']);
                            $currentDateTime = time();
                            $editable = ($appointmentDateTime - $currentDateTime) > (24 * 3600); // 24 hours in seconds
                            $isPastAppointment = ($appointmentDateTime < $currentDateTime);

                            ?>
                            <tr class="clickable-row" data-bs-toggle="modal" data-bs-target="#appointmentModal_<?php echo $appointment['appointmentID']; ?>">
                                <td><?php echo htmlspecialchars($appointment['date_preference']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['time_preference']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['appointment_type']); ?></td>
                                <td><?php echo $appointment['approved'] ? 'Yes' : 'No'; ?></td>
                            </tr>

                            <div class="modal fade" id="appointmentModal_<?php echo $appointment['appointmentID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Appointment Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="detailsSection_<?php echo $appointment['appointmentID']; ?>">
                                                <p>Date: <?php echo htmlspecialchars($appointment['date_preference']); ?></p>
                                                <p>Time: <?php echo htmlspecialchars($appointment['time_preference']); ?></p>
                                                <p>Appointment Type: <?php echo htmlspecialchars($appointment['appointment_type']); ?></p>
                                                <p>Reason: <?php echo htmlspecialchars($appointment['reason']); ?></p>
                                                <p>Approved: <?php echo $appointment['approved'] ? 'Yes' : 'No'; ?></p>
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
                                                        <label for="editAppointmentType_<?php echo $appointment['appointmentID']; ?>" class="form-label">Appointment Type</label>
                                                        <input type="text" class="form-control" id="editAppointmentType_<?php echo $appointment['appointmentID']; ?>" name="appointment_type" value="<?php echo htmlspecialchars($appointment['appointment_type']); ?>" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="editReason_<?php echo $appointment['appointmentID']; ?>" class="form-label">Reason</label>
                                                        <textarea class="form-control" id="editReason_<?php echo $appointment['appointmentID']; ?>" name="reason" rows="3" required><?php echo htmlspecialchars($appointment['reason']); ?></textarea>
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- Additional details as needed -->
                                        </div>
                                        <div class="modal-footer">
                                            <div class="row">
                                                <div class="col text-end">
                                                    <?php if ($isPastAppointment): ?>
                                                        <a href="view_prescription.php?appointment_id=<?php echo $appointment['patientID']; ?>" class="btn btn-primary text-decoration-none">
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
                                    // Submit the form
                                    document.getElementById('editAppointmentForm_' + appointmentID).submit();
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
                </tbody>
            </table>
        </div>
    </section>

    <!-- Your scripts can go here if needed -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
