<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinic Landing Page</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Merriweather', serif;
        }
        /* Custom CSS */
        .navbar { background-color: #f8f9fa; }
        .navbar-brand img { height: 100px; }
        .header { 
            text-align: center; 
            padding: 50px 20px; 
            background-image: url('../img/background.png'); 
            background-size: cover; 
            background-position: center; 
            color: white; /* Ensure text is visible over background */
        }
        .steps { padding: 20px 0; }
        .step { padding: 20px; text-align: center; }
        .step h5 { margin-top: 10px; } 
        .footer { 
            background-color: #12229D; /* Blue background color */
            padding: 20px; 
            text-align: center; 
            color: white; /* White text color */
        }

        /* Adjusted primary button style */
        .btn-primary {
            color: #fff;
            background-color: #12229D;
            border: 2px solid #12229D;
            padding: 10px 20px; /* Custom padding */
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
                <li class="nav-item">
                    <a class="btn btn-primary" href="loginForm.php">Login</a>
                </li>
            </ul>
        </div>
    </nav>

    <header class="header">
        <div class="container">
            <h3>Welcome to Our Clinic</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum vehicula ex euismod eros consectetur, non tempor ex euismod.</p>
            <a href="book-appointment.php" class="btn btn-primary">Book an Appointment</a>
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

    <footer class="footer">
        <div class="container">
            <p>Contact Us: (123) 456-7890 | email@clinic.com</p>
            <p>&copy; 2024 Our Clinic. All rights reserved.</p>
        </div>
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
