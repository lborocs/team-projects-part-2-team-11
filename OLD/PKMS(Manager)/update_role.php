<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user_email_update and role_update from the form
    $user_email_update = $_POST['user_email_update'];
    $role_update = $_POST['role_update'];

    // Connect to your database
// Database connection details
$servername = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Update the user's role in the database
    // $sql = "UPDATE Users_Details SET role='$role_update' WHERE user_email='$user_email_update'";
    // if ($conn->query($sql) === TRUE) {
    //     echo "User role updated successfully.";
    // } else {
    //     echo "Error updating user role: " . $conn->error;
    // }

    // Update the user's role in the database
    $sql = "UPDATE Users_Details SET role='$role_update' WHERE user_email='$user_email_update'";
    if ($conn->query($sql) === TRUE) {
        echo "success"; // Return success message
    } else {
        echo "Error updating user role: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
