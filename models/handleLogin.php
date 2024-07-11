<?php
include 'db.php';

// Define secretary credentials
$secretaryEmail = "secretary@gmail.com";
$secretaryPassword = "secretaryPassword";

// Initialize variables for storing user input and errors
$email = $password = "";
$errors = array();

// Process form submission when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate input data
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate form inputs
    if (empty($email)) {
        $errors[] = "Email is required";
    }
    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // If no errors, attempt to fetch user from database or check if secretary credentials
    if (empty($errors)) {
        // Check if the user is the secretary
        if ($email == $secretaryEmail && $password == $secretaryPassword) {
            // Authentication successful, redirect to secretary.php
            session_start();
            $_SESSION["email"] = $email; // Store email in session for future use if needed
            header("Location: ../pages/secretary.php");
            exit();
        }

        // Otherwise, proceed with patient login
        $sql = "SELECT * FROM patient WHERE email='$email'";
        $result = $conn->query($sql);

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Password is correct, start session and set session variables
                session_start();
                $_SESSION['patientID'] = $row['patientID'];
                $_SESSION['email'] = $email;
                $_SESSION['patient_name'] = $row['first_name'];

                // Redirect to patient dashboard
                header("Location: ../pages/patientDashboard.html");
                exit();
            } else {
                $errors[] = "Incorrect password";
            }
        } else {
            $errors[] = "User not found with this email";
        }
    }
}

// Redirect back to login page with errors if any
if (!empty($errors)) {
    session_start();
    $_SESSION['errors'] = $errors;
    header("Location: ../pages/login.html");
    exit();
}
?>
