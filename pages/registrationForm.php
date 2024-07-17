<?php
session_start();

// Check for email exists message
if (isset($_SESSION['email_exists']) && $_SESSION['email_exists']) {
    echo "<script>alert('" . $_SESSION['email_exists_message'] . "');</script>";
    unset($_SESSION['email_exists']);
    unset($_SESSION['email_exists_message']);
}

if (isset($_SESSION['phone_exists']) && $_SESSION['phone_exists']) {
    echo "<script>alert('" . $_SESSION['phone_exists_message'] . "');</script>";
    unset($_SESSION['phone_exists']);
    unset($_SESSION['phone_exists_message']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>Medical Clinic - Register</title>
    <link rel="icon" type="image/x-icon" href="img/logo.png">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: "Merriweather", serif;
            background-image: url('../img/background.png');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            padding: 0;
        }
        .container {
            max-height: 80vh;
            overflow-y: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
        }
        .form-control {
            font-family: "Merriweather", serif;
            margin-bottom: 10px;
        }
        /* Placeholder style for input[type="date"] */
        input[type="date"]::before {
            content: "Date of Birth";
            color: #6c757d; /* Adjust color as needed */
        }
        .error {
            color: red;
            font-size: 0.8em;
            margin-bottom: 15px;
        }
        .guideline-title {
            color: gray;
            font-size: 0.8em;
            margin-left: 10px;
            margin-bottom: 5px;
        }
        .guideline {
            color: red;
            font-size: 0.8em;
            margin-left: 10px;
            margin-bottom: 5px;
            display: block;
        }
        .guideline::before {
            content: '\2718'; /* Red cross */
            color: red;
            margin-right: 5px;
        }
        .guideline.valid {
            color: green;
        }
        .guideline.valid::before {
            content: '\2714'; /* Green checkmark */
            color: green;
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
        .text-center {
            text-align: center;
        }
        .back-btn {
            color: #6e6e6e; /* Set the color of the icon */
        }
        .back-btn:hover {
            color: #929292; /* Hover color */
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="loginForm.php" class="back-btn">
            <i class="fas fa-arrow-left fa-lg"></i>
        </a>
        <div id="Register">
            <h2 class="mb-3 mt-3">Register</h2>
            <form action="../models/handleRegister.php" method="post">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <input type="text" class="form-control" id="fname" name="first_name" placeholder="First name" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <input type="text" class="form-control" id="lname" name="last_name" placeholder="Last name" required>
                    </div>
                </div>
                <div class="mb-1">
                    <input type="text" class="form-control mb-4" id="address" name="address" placeholder="Address" required>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                    <div class="col-md-6 mb-2">
                        <input type="tel" class="form-control" id="phone" name="phone_number" pattern="[0-9]{4} [0-9]{3} [0-9]{4}" placeholder="Phone number (e.g. 0917 123 4567)" oninput="formatPhoneNumber(this)" maxlength="13" required>
                    </div>
                </div>
                <div class="mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                    <span id="emailError" class="error"></span>
                </div>
                <div class="mb-2">
                    <input type="password" class="form-control" id="psw" name="password" pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$" placeholder="Password" required>
                    <div id="passwordGuidelines">
                        <p class="guideline-title">Password must have at least:</p>
                        <p id="lengthGuideline" class="guideline">8 characters</p>
                        <p id="lowercaseGuideline" class="guideline">one lowercase letter</p>
                        <p id="uppercaseGuideline" class="guideline">one uppercase letter</p>
                        <p id="numberGuideline" class="guideline">one number</p>
                        <p id="specialCharGuideline" class="guideline">one special character</p>
                        <!-- Add more guidelines as needed -->
                    </div>
                </div>
                <div class="mb-2 form-check">
                    <input type="checkbox" class="form-check-input" id="showPasswordCheckbox" onclick="showHidePass()">
                    <label class="form-check-label" for="showPasswordCheckbox">Show Password</label>
                </div>
                <div class="mb-4">
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
                    <span id="passwordError" class="password-error error"></span>
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <button type="submit" class="btn btn-primary btn-block w-100">Register</button>
                    </div>                                                      
                </div>
                <p class="text-center mt-3">Already have an account? <a href="javascript:void(0);" onclick="showLogin()" class="loginlink">Login</a></p>
            </form>
        </div>
    </div>

    <script>
        function showLogin() {
            window.location.href = 'loginForm.php'; // Redirect to the login form
        }

        function showHidePass() {
            const passwordField = document.getElementById('psw');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
            } else {
                passwordField.type = 'password';
            }
        }

        document.getElementById('confirmPassword').addEventListener('input', function() {
            const password = document.getElementById('psw').value;
            const confirmPassword = this.value;
            const passwordError = document.getElementById('passwordError');
            const registerBtn = document.querySelector('button[type="submit"]');

            if (password !== confirmPassword) {
                passwordError.textContent = 'Passwords do not match';
                registerBtn.disabled = true;
            } else {
                passwordError.textContent = '';
                registerBtn.disabled = false;
            }
        });

        document.getElementById('email').addEventListener('input', function() {
            const email = this.value;
            const emailError = document.getElementById('emailError');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                emailError.textContent = 'Invalid email format';
            } else {
                emailError.textContent = '';
            }
        });

        function formatPhoneNumber(input) {
            var cleaned = input.value.replace(/\D/g, '');
            var formattedNumber = '';
            if (cleaned.length > 0) {
                if (cleaned.length <= 4) {
                    formattedNumber = cleaned;
                } else if (cleaned.length <= 7) {
                    formattedNumber = cleaned.slice(0, 4) + ' ' + cleaned.slice(4);
                } else {
                    formattedNumber = cleaned.slice(0, 4) + ' ' + cleaned.slice(4, 7) + ' ' + cleaned.slice(7);
                }
            }
            input.value = formattedNumber;
            if (input.value.trim() === '') {
                input.value = '';
            }
        }

        const passwordInput = document.getElementById('psw');
        const lengthGuideline = document.getElementById('lengthGuideline');
        const lowercaseGuideline = document.getElementById('lowercaseGuideline');
        const uppercaseGuideline = document.getElementById('uppercaseGuideline');
        const numberGuideline = document.getElementById('numberGuideline');
        const specialCharGuideline = document.getElementById('specialCharGuideline');

        passwordInput.addEventListener('input', function() {
            const password = this.value;

            // Reset guidelines
            lengthGuideline.classList.remove('valid');
            lowercaseGuideline.classList.remove('valid');
            uppercaseGuideline.classList.remove('valid');
            numberGuideline.classList.remove('valid');
            specialCharGuideline.classList.remove('valid');

            // Check each guideline
            if (password.length >= 8) {
                lengthGuideline.classList.add('valid');
            }
            if (/[a-z]/.test(password)) {
                lowercaseGuideline.classList.add('valid');
            }
            if (/[A-Z]/.test(password)) {
                uppercaseGuideline.classList.add('valid');
            }
            if (/\d/.test(password)) {
                numberGuideline.classList.add('valid');
            }
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                specialCharGuideline.classList.add('valid');
            }
        });

        // Select the password input field
        const passwordField = document.getElementById('psw');
        const passwordGuidelines = document.getElementById('passwordGuidelines');

        // Function to show password guidelines
        function showPasswordGuidelines() {
            passwordGuidelines.style.display = 'block';
        }

        // Function to hide password guidelines
        function hidePasswordGuidelines() {
            passwordGuidelines.style.display = 'none';
        }

        // Event listener to show guidelines when password field is focused
        passwordInput.addEventListener('focus', showPasswordGuidelines);

        // Event listener to hide guidelines when password field loses focus
        passwordInput.addEventListener('blur', hidePasswordGuidelines);

        // Event listener to continuously update guidelines based on input
        passwordInput.addEventListener('input', function() {
            const password = this.value;

            // Reset guidelines
            lengthGuideline.classList.remove('valid');
            lowercaseGuideline.classList.remove('valid');
            uppercaseGuideline.classList.remove('valid');
            numberGuideline.classList.remove('valid');
            specialCharGuideline.classList.remove('valid');

            // Check each guideline
            if (password.length >= 8) {
                lengthGuideline.classList.add('valid');
            }
            if (/[a-z]/.test(password)) {
                lowercaseGuideline.classList.add('valid');
            }
            if (/[A-Z]/.test(password)) {
                uppercaseGuideline.classList.add('valid');
            }
            if (/\d/.test(password)) {
                numberGuideline.classList.add('valid');
            }
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                specialCharGuideline.classList.add('valid');
            }
        });

        // Initially hide guidelines on page load
        hidePasswordGuidelines();

    </script>
</body>
</html>
