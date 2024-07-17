<?php
include '../Models/db.php'; // Include database configuration

// Fetch appointments for the logged-in user
$user_id = $_SESSION['patientID'] ?? null;
$appointments = array();

if ($user_id) {
    // Get today's date
    $today = date('Y-m-d');

    // Retrieve actual data with limit for 5 most recent appointments from today
    $sql = "SELECT * FROM appointments WHERE patientID = ? AND date_preference <= ? ORDER BY date_preference DESC, time_preference DESC LIMIT 5";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "is", $user_id, $today);
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
        .table {
            text-align: center;
            border: 1px solid grey;
        }
        .table th {
            background-color: #12229D; /* Background color for the column headers */
            color: #fff; /* Text color for the column headers */
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
        .status-complete {
            color: green !important;
            font-weight: bold;
        }
        .status-pending {
            color: red !important;
            font-weight: bold;
        }
        .label-date, .label-time, .label-type, .label-reason, .label-status {
            font-weight: bold; /* Optional: Make labels bold */
            margin-bottom: 5px; /* Optional: Adjust spacing */
            margin-right: 5px;
        }
        .modal-title {
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .table {
                font-size: 0.8rem; /* Font size for tablets */
            }
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Additional custom scripts -->
    <script>
        function toggleDetails(appointmentID) {
            $('#detailsSection_' + appointmentID).hide();
            // Hide edit section (if shown)
            $('#editSection_' + appointmentID).hide();
            // Show prescription section
            $('#prescriptionSection_' + appointmentID).show();
        }

        function toggleModal(appointmentID) {
            $('#detailsSection_' + appointmentID).show();
            // Hide edit section (if shown)
            $('#editSection_' + appointmentID).hide();
            // Show prescription section
            $('#prescriptionSection_' + appointmentID).hide();
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

        $(document).ready(function() {
            $('.fetch-prescription-btn').click(function(e) {
                e.preventDefault();
                var appointmentID = $(this).data('appointment-id'); // Get appointmentID from data attribute or URL params
                var prescriptionSection = $('#prescriptionSection_' + appointmentID);

                // AJAX request to fetch prescription
                $.ajax({
                    url: '../Models/fetchPrescriptions.php',
                    type: 'GET',
                    data: {
                        appointmentID: appointmentID
                    },
                    dataType: 'html',
                    success: function(response) {
                        prescriptionSection.html(response); // Populate prescription content
                        prescriptionSection.show(); // Show prescription section
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching prescription:', error);
                        // Optionally handle errors here
                    }
                });
            });
        });
    </script>
</head>
<body>
    <section class="container content-container">
        <h3>Recent Appointments</h3>
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

                        <div class="modal fade" id="appointmentModal_<?php echo $appointment['appointmentID']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Appointment Details</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" onclick="toggleModal('<?php echo $appointment['appointmentID']; ?>')"></button>
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
                                        <div id="prescriptionSection_<?php echo $appointment['appointmentID']; ?>" style="display: none;">
                                            <!-- Content for displaying prescriptions -->
                                            <div class="mb-3">
                                                <label for="prescriptionText_<?php echo $appointment['appointmentID']; ?>" class="form-label">Prescription Text</label>
                                                <textarea class="form-control" id="prescriptionText_<?php echo $appointment['appointmentID']; ?>" rows="3" readonly><?php echo htmlspecialchars($prescription['prescription_text']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="doctorsNotes_<?php echo $appointment['appointmentID']; ?>" class="form-label">Doctor's Notes</label>
                                                <textarea class="form-control" id="doctorsNotes_<?php echo $appointment['appointmentID']; ?>" rows="3" readonly><?php echo htmlspecialchars($prescription['doctors_notes']); ?></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="diagnosis_<?php echo $appointment['appointmentID']; ?>" class="form-label">Diagnosis</label>
                                                <textarea class="form-control" id="diagnosis_<?php echo $appointment['appointmentID']; ?>" rows="3" readonly><?php echo htmlspecialchars($prescription['diagnosis']); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="row">
                                            <div class="col text-end">
                                            <?php if ($appointment['approved'] == 1 && $appointment['completed'] == 1 && !$editable): ?>
                                                <button type="button" class="btn btn-primary fetch-prescription-btn" data-appointment-id="<?php echo $appointment['appointmentID']; ?>">
                                                    <i class="fas fa-file-prescription fa-lg"></i> Prescription
                                                </button>
                                            <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</body>
</html>

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
