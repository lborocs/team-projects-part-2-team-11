<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user_email_update and role_update from the form
    $user_email_update = $_POST['user_email_update'];
    $role_update = $_POST['role_update'];

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

    // Check if the user is currently managing any active projects
    $sql = "SELECT * FROM Project WHERE manager = '$user_email_update' AND project_status = 'INCOMPLETE'";
    $result = $conn->query($sql);

    // If the user is managing active projects, prevent role update and display an alert message
    if ($result->num_rows > 0 && $role_update === 'Employee') {
        echo "User is currently managing active projects. Role cannot be updated.";
    } else {
        // Update the user's role in the database
        $sql = "UPDATE Users_Details SET role='$role_update' WHERE user_email='$user_email_update'";
        if ($conn->query($sql) === TRUE) {
            echo "success"; // Return success message
        } else {
            echo "Error updating user role: " . $conn->error;
        }
    }

    // Close the database connection
    $conn->close();
}
?>
