<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Clinic - Home</title>
    <link rel="icon" type="image/x-icon" href="../img/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <style>
        .header { 
            text-align: center; 
            padding: 50px 20px; 
            background-image: url('../img/background.png'); 
            background-size: cover; 
            background-position: center; 
            color: white; /* Ensure text is visible over background */
        }
        .steps { 
            padding: 20px 0;
            color: #12229D; 
        }
        .step { padding: 20px; text-align: center; }
        .step h5 { 
            margin-top: 10px; 
            font-weight: 700;
        }
        .steps i { color: #12229D; } 
        .faq-link {
            margin-right: 30px;
            color: #12229D;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../img/horizontallogo.png" alt="Clinic Logo">
            </a>
            <ul class="navbar-nav ms-auto mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link faq-link" href="faq.php">FAQ</a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="btn btn-primary" href="loginForm.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <header class="header">
        <div class="container">
            <h3>Welcome to Our Clinic</h3>
            <p>Providing compassionate care and excellence in medical services.</p>
            <a href="loginForm.php" class="btn btn-primary">Book an Appointment</a>
        </div>
    </header>

    <section class="steps">
        <div class="container">
            <div class="row">
                <div class="col-md-4 step">
                    <i class="fas fa-user-md fa-3x"></i>
                    <h5>Step 1: Register</h5>
                    <p>Sign up and create your profile to get started.</p>
                </div>
                <div class="col-md-4 step">
                    <i class="fas fa-calendar-check fa-3x"></i>
                    <h5>Step 2: Book Appointment</h5>
                    <p>Choose your preferred date and time for the appointment.</p>
                </div>
                <div class="col-md-4 step">
                    <i class="fas fa-notes-medical fa-3x"></i>
                    <h5>Step 3: Get Treated</h5>
                    <p>Visit our clinic and receive top-notch medical care.</p>
                </div>
            </div>
        </div>
    </section>

    <a href="#" id="btnScrollToTop" class="btn-scroll-top">
        <i class="fas fa-arrow-up"></i>
    </a>

    <!-- Footer Section -->
    <?php include '../components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/scroll-to-top.js"></script>
</body>
</html>
