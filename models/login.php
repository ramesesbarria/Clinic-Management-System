<?php
// Prevent caching of sensitive pages
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

session_start();
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validate and sanitize input (example)
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    // Query to check if the email and password are valid for patients
    $query_patient = "SELECT * FROM patient WHERE email = ?";
    $stmt_patient = $conn->prepare($query_patient);
    $stmt_patient->bind_param("s", $email);
    $stmt_patient->execute();
    $result_patient = $stmt_patient->get_result();

    if ($result_patient->num_rows > 0) {
        $user_patient = $result_patient->fetch_assoc();
        if (password_verify($password, $user_patient['password'])) {
            // Successful login for patient
            $_SESSION['user_id'] = $user_patient['patientID'];
            $_SESSION['user_first_name'] = $user_patient['first_name'];
            $_SESSION['user_last_name'] = $user_patient['last_name'];
            $_SESSION['user_dob'] = $user_patient['dob'];
            $_SESSION['user_address'] = $user_patient['address'];
            $_SESSION['user_phone'] = $user_patient['phone_number'];
            $_SESSION['user_email'] = $user_patient['email'];
            $_SESSION['last_login'] = time();
            header("Location: ../Pages/landing_page.php");
            exit();
        } else {
            // Incorrect password
            $_SESSION['login_error'] = 'Invalid email or password';
        }
    } else {
        // Query to check if the email and password are valid for staff
        $query_staff = "SELECT * FROM staff WHERE email = ?";
        $stmt_staff = $conn->prepare($query_staff);
        $stmt_staff->bind_param("s", $email);
        $stmt_staff->execute();
        $result_staff = $stmt_staff->get_result();

        if ($result_staff->num_rows > 0) {
            $user_staff = $result_staff->fetch_assoc();
            if (password_verify($password, $user_staff['password'])) {
                // Successful login for staff
                $_SESSION['staff_id'] = $user_staff['staffID'];
                $_SESSION['staff_first_name'] = $user_staff['fname'];
                $_SESSION['last_login'] = time();

                // Determine the dashboard based on staff type
                switch ($user_staff['staffType']) {
                    case 'secretary':
                        header("Location: ../Pages/secretary_dashboard.php");
                        exit();
                    case 'doctor':
                        header("Location: ../Pages/doctor_dashboard.php");
                        exit();
                    default:
                        // Handle other staff types if necessary
                        header("Location: ../Pages/landing_page.php");
                        exit();
                }
            } else {
                // Incorrect password for staff
                $_SESSION['login_error'] = 'Invalid email or password';
            }
        } else {
            // Email not found in both patient and staff tables
            $_SESSION['login_error'] = 'Invalid email or password';
        }
    }

    // Redirect to login page after login attempt
    header("Location: ../Pages/loginForm.php");
    exit();
}
?>
