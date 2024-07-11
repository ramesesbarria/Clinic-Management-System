<!-- handleRegister.php -->

<?php
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
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // If no errors, insert user into database
    if (empty($errors)) {
        // Check if email is already registered
        $sql_check_email = "SELECT * FROM patient WHERE email='$email'";
        $result_check_email = $conn->query($sql_check_email);

        if ($result_check_email->num_rows > 0) {
            $errors[] = "Email already exists, please choose a different one";
        } else {
            // Hash password before storing in database
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into database
            $sql_insert_user = "INSERT INTO patient (first_name, last_name, dob, address, phone_number, email, password) 
                                VALUES ('$first_name', '$last_name', '$dob', '$address', '$phone_number', '$email', '$hashed_password')";

            if ($conn->query($sql_insert_user) === TRUE) {
                // Registration successful, redirect to login page
                header("Location: ../pages/login.html");
                exit();
            } else {
                $errors[] = "Error: " . $sql_insert_user . "<br>" . $conn->error;
            }
        }
    }
}

// Redirect back to registration page with errors if any
if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    header("Location: ../pages/register.html");
    exit();
}
?>
