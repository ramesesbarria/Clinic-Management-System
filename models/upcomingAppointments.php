<?php include 'db.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upcoming Appointments</title>
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
    </style>
</head>
<body>
    <section class="container content-container mb-4">
        <h2>Upcoming Appointments</h2>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Appointment Type</th>
                        <th>Reason</th>
                        <th>Approved</th>
                        <th>Prescription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Fetch appointments for the logged-in user
                        $user_id = $_SESSION['user_id'] ?? null;
                        $appointments = array();

                        if ($user_id) {
                            $sql = "SELECT * FROM appointments 
                                    WHERE date_preference > CURDATE() 
                                    OR (date_preference = CURDATE() AND time_preference > CURTIME())
                                    ORDER BY date_preference ASC, time_preference ASC";

                            $result = mysqli_query($conn, $sql);

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $appointments[] = $row;
                                }
                            }
                        }

                        // Display appointments in the table
                        foreach ($appointments as $appointment):
                            // Calculate if appointment is more than 24 hours away
                            $appointmentDateTime = strtotime($appointment['date_preference'] . ' ' . $appointment['time_preference']);
                            $currentDateTime = time();
                            $editable = ($appointmentDateTime - $currentDateTime) > (24 * 3600); // 24 hours in seconds

                            ?>
                            <tr class="clickable-row" data-bs-toggle="modal" data-bs-target="#appointmentModal_<?php echo $appointment['appointmentID']; ?>">
                                <td><?php echo htmlspecialchars($appointment['date_preference']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['time_preference']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['appointment_type']); ?></td>
                                <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                                <td><?php echo $appointment['approved'] ? 'Yes' : 'No'; ?></td>
                                <td>
                                    <a href="view_prescription.php?appointment_id=<?php echo $appointment['patientID']; ?>" class="text-decoration-none">
                                        <i class="fas fa-file-prescription fa-lg" style="color: #12229D;"></i> <!-- Font Awesome prescription icon -->
                                    </a>
                                </td>
                                <td>
                                    <?php if ($editable): ?>
                                        <a href="#" onclick="editAppointment('<?php echo $appointment['appointmentID']; ?>')"><i class="fas fa-edit fa-lg" style="color: green;"></i></a>
                                        <a href="#" onclick="cancelAppointment('<?php echo $appointment['appointmentID']; ?>')"><i class="fas fa-trash-alt fa-lg" style="color: #dc3545;"></i></a>
                                    <?php endif; ?>
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
                                            <p>Date: <?php echo htmlspecialchars($appointment['date_preference']); ?></p>
                                            <p>Time: <?php echo htmlspecialchars($appointment['time_preference']); ?></p>
                                            <p>Appointment Type: <?php echo htmlspecialchars($appointment['appointment_type']); ?></p>
                                            <p>Reason: <?php echo htmlspecialchars($appointment['reason']); ?></p>
                                            <p>Approved: <?php echo $appointment['approved'] ? 'Yes' : 'No'; ?></p>
                                            <!-- Additional details as needed -->
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal for Editing Appointment -->
                            <div class="modal fade" id="editAppointmentModal_<?php echo $appointment['appointmentID']; ?>" tabindex="-1" aria-labelledby="editAppointmentModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editAppointmentModalLabel">Edit Appointment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
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
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" onclick="submitForm('<?php echo $appointment['appointmentID']; ?>')">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function submitForm(appointmentID) {
                                    $('#editAppointmentForm_' + appointmentID).submit();
                                }
                                function cancelAppointment(appointmentID) {
                                    if (confirm('Are you sure you want to cancel this appointment?')) {
                                        $.post('../models/cancel_appointment.php', { appointmentID: appointmentID }, function(data) {
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
