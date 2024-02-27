<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user_email from the form
    $user_email = $_POST['reset_user'];

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

    // Reset user logins by setting password, username, and secure answer to null
    $sql = "UPDATE Users_Details SET password=NULL, username=NULL, secure_ans=NULL WHERE user_email='$user_email'";
    if ($conn->query($sql) === TRUE) {
        echo "success"; // Return success message
    } else {
        echo "Error resetting user logins: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>
