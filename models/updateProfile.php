<?php
session_start(); // Ensure session is started

include '../Models/db.php'; // Include database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialize variables with form data
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $dob = $_POST['dob'];
    $address = $_POST['address'];
    $phoneNumber = $_POST['phoneNumber'];
    $email = $_POST['email'];

    // Update session variables
    $_SESSION['user_first_name'] = $firstName;
    $_SESSION['user_last_name'] = $lastName;
    $_SESSION['user_dob'] = $dob;
    $_SESSION['user_address'] = $address;
    $_SESSION['user_phone'] = $phoneNumber;
    $_SESSION['user_email'] = $email;

    // Prepare SQL statement to update user profile in the database
    $stmt = $conn->prepare("UPDATE patient SET first_name=?, last_name=?, dob=?, address=?, phone_number=?, email=? WHERE patientID=?");

    // Bind parameters and execute the statement
    $stmt->bind_param("ssssssi", $firstName, $lastName, $dob, $address, $phoneNumber, $email, $_SESSION['user_id']);
    if ($stmt->execute()) {
        // Redirect back to edit_profile.php on success
        header("Location: ../Pages/editProfile.php");
        exit();
    } else {
        // Handle error (e.g., display an error message)
        echo "Error updating profile: " . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close database connection
$conn->close();
?>
