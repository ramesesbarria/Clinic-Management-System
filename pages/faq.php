<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Clinic - FAQs</title>
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
        .faq-link {
            margin-right: 30px;
            color: #12229D;
            font-weight: 700;
        }
        .container h1 {
            color: #fff;
            margin-bottom: 20px;
        }
        .accordion-button, .accordion-button.collapsed {
            position: relative;
            font-weight: 700;
            color: #12229D; /* Default background color */
        }
        .back-btn {
            color: #fff !important;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="../Pages/index.php">
                <img src="../img/horizontallogo.png" alt="Clinic Logo">
            </a>

            <?php
                session_start();
                // Check if user is logged in (you need a way to determine this, such as session variables)
                $isLoggedIn = isset($_SESSION['patientID']); // Example condition to check if user is logged in
                
                if ($isLoggedIn) {
                    // User is logged in, show profile dropdown
                    echo '
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
                    </ul>';
                } else {
                    // User is not logged in, show login button
                    echo '
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="btn btn-primary" href="loginForm.php">Login</a>
                        </li>
                    </ul>';
                }
            ?>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <?php
        
        if ($isLoggedIn) {
            echo '<a href="landingPage.php" class="back-btn">
                    <i class="fas fa-arrow-left fa-lg"></i>Return to dashboard
                </a>';
        } else {
            echo '<a href="index.php" class="back-btn">
                    <i class="fas fa-arrow-left fa-lg"></i>Back to home
                </a>';
        }
        ?>
        <h1 class="mt-2">Frequently Asked Questions</h1>

        <div class="accordion" id="faqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        How to book an appointment?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body" style="font-size: 0.9rem;">
                        <p>To book an appointment through our website you may follow these steps:</p>
                        <ol>
                            <li class="mb-2"><strong>Log In:</strong> If you have an account, log in using your credentials. If not, you may need to create an account first.</li>
                            <li class="mb-2"><strong>Open Appointment Form:</strong> Once logged in, click on the Book an Appointment button.</li>
                            <li class="mb-2"><strong>Select Date, Time, and Service:</strong> Choose your preferred appointment date and time from the available options. Select the service you require and describe further the care you need in the Reason for Appointment field.</li>
                            <li class="mb-2"><strong>Confirm details:</strong> After selecting your appointment details, review and confirm your booking.</li>
                            <li class="mb-2"><strong>Submit Appointment Form:</strong> Once confirmed, you may submit the form and check the status of your booking in the dashboard.</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        What happens if I want to make some changes to, reschedule, or cancel my appointment?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body" style="font-size: 0.9rem;">
                        <p>We understand that plans can change. If you need to reschedule or cancel your appointment, you may edit your existing appointment bookings.</p>
                        <ol>
                            <li class="mb-2"><strong>Select Appointment:</strong> From the dashboard, head to Upcoming Appointments and click the appointment you would like to change. </li>
                            <li class="mb-2"><strong>Choose option:</strong> On the bottom of the modal, you may click either the Edit button to reschedule or make some changes to the appointment details and cancel the appointment.  </li>
                            <li class="mb-2"><strong>Confirmation:</strong> After making the necessary changes, confirm the details are accurate and agree to the following confirmation messages.</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        What are the office hours?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body" style="font-size: 0.9rem;">
                        <p>Our clinic is open during the following hours:</p>
                        <ul>
                            <li class="mb-2"><strong>Monday to Friday:</strong> 8:00 AM - 5:00 PM</li>
                            <li class="mb-2"><strong>Saturday:</strong> 9:00 AM - 1:00 PM</li>
                            <li class="mb-2"><strong>Sunday:</strong> Closed</li>
                        </ul>
                        <p>We are closed on public holidays. Please note that appointment hours may vary. For specific appointment availability, please check our online booking system or contact us directly.</p>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-center mt-3" style="color: #fff; font-weight: 700;">If you encounter any issues or have questions during the booking process, please don't hesitate to contact us for assistance.</p>
    </div>

    <a href="#" id="btnScrollToTop" class="btn-scroll-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Footer Section -->
    <?php include '../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scroll-to-top.js"></script>
<script>
        // Toggle icon on accordion collapse/expand
        document.addEventListener('DOMContentLoaded', function () {
            const accordionItems = document.querySelectorAll('.accordion-item');

            accordionItems.forEach(item => {
                item.addEventListener('click', function () {
                    const icon = this.querySelector('i');
                    icon.classList.toggle('fa-plus');
                    icon.classList.toggle('fa-minus');
                });
            });
        });
    </script>

</body>
</html>
