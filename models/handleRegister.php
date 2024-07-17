<?php
session_start();
include 'db.php';

// Initialize variables for storing user input and errors
$first_name = $last_name = $dob = $address = $phone_number = $email = $password = "";
$errors = array();

// Process form submission when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $dob = mysqli_real_escape_string($conn, $_POST['dob']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate form inputs
    if (empty($first_name)) {
        $errors[] = "First Name is required";
    }
    if (empty($last_name)) {
        $errors[] = "Last Name is required";
    }
    if (empty($dob)) {
        $errors[] = "Date of Birth is required";
    }
    if (empty($address)) {
        $errors[] = "Address is required";
    }
    if (empty($phone_number)) {
        $errors[] = "Phone Number is required";
    }
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    } elseif (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long and contain at least one lowercase letter, one uppercase letter, one number, and one special character.";
    }

    // If no errors, check email existence and insert user into database
    if (empty($errors)) {
        // Check if email is already registered
        $sql_check_email = "SELECT * FROM patient WHERE email='$email'";
        $result_check_email = $conn->query($sql_check_email);

        if ($result_check_email->num_rows > 0) {
            // Email already exists, prepare message
            $_SESSION['email_exists_message'] = "Email already exists, please choose a different one";
            $_SESSION['email_exists'] = true; // Flag to indicate email exists error
            header("Location: ../pages/registrationForm.php");
            exit();
        }

        // Check if phone number is already registered
        $sql_check_phone = "SELECT * FROM patient WHERE phone_number='$phone_number'";
        $result_check_phone = $conn->query($sql_check_phone);

        if ($result_check_phone->num_rows > 0) {
            // Phone number already exists, prepare message
            $_SESSION['phone_exists_message'] = "Phone number already exists, please choose a different one";
            $_SESSION['phone_exists'] = true; // Flag to indicate phone number exists error
            header("Location: ../pages/registrationForm.php");
            exit();
        }
        // Hash password before storing in database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert new user into database
        $sql_insert_user = "INSERT INTO patient (first_name, last_name, dob, address, phone_number, email, password) 
                            VALUES ('$first_name', '$last_name', '$dob', '$address', '$phone_number', '$email', '$hashed_password')";

        if ($conn->query($sql_insert_user) === TRUE) {
            // Get the patientID of the newly registered user
            $patientID = $conn->insert_id;

            // Set the patientID in session
            $_SESSION['patientID'] = $patientID;

            // Set success message for JavaScript alert
            $_SESSION['registration_success_message'] = "Registered successfully!";
            $_SESSION['registration_success'] = true;

            // Redirect to login page (or wherever needed after successful registration)
            header("Location: ../pages/loginForm.php");
            exit();
        } else {
            $errors[] = "Error: " . $sql_insert_user . "<br>" . $conn->error;
        }
    }
}

// Redirect back to registration page with errors if any
$_SESSION['errors'] = $errors;
header("Location: ../pages/registrationForm.php");
exit();
?>
