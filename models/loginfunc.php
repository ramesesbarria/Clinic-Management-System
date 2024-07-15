<?php
// Start the session
session_start();

// Include the database connection file
require 'db.php';

// Function to sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

// Check if POST request is made
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = sanitizeInput($_POST['email']);
    $password = sanitizeInput($_POST['password']);

    // Check if email and password are provided
    if (!empty($email) && !empty($password)) {
        // Prepare and execute query to check in the patient table
        $stmt = $conn->prepare("SELECT patientID, password FROM patient WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($patientID, $hashedPassword);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $hashedPassword)) {
                // Password is correct, set session variables and redirect to patient dashboard
                $_SESSION['userID'] = $patientID;
                $_SESSION['userType'] = 'patient';
                header("Location: patient_dashboard.php");
                exit();
            } else {
                echo "Invalid password.";
            }
        } else {
            // If not found in patient table, check in the staff table
            $stmt = $conn->prepare("SELECT staffID, password, staffType FROM staff WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($staffID, $hashedPassword, $staffType);

            if ($stmt->num_rows > 0) {
                $stmt->fetch();
                if (password_verify($password, $hashedPassword)) {
                    // Password is correct, set session variables and redirect based on staff type
                    $_SESSION['userID'] = $staffID;
                    $_SESSION['userType'] = $staffType;

                    if ($staffType == 'doctor') {
                        header("Location: ..\pages\doctor_dashboard.php");
                    } elseif ($staffType == 'secretary') {
                        header("Location: secretary_dashboard.php");
                    } elseif ($staffType == 'admin') {
                        header("Location: admin_dashboard.php");
                    }
                    exit();
                } else {
                    echo "Invalid password.";
                }
            } else {
                echo "No user found with that email address.";
            }
        }
        $stmt->close();
    } else {
        echo "Please enter email and password.";
    }
}
$conn->close();
?>