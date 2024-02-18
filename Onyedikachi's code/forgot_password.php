<?php
session_start(); // Start session if not already started


error_reporting(E_ALL);
ini_set('display_errors', 1);

// Set up the connection to the database
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$servername = "localhost";
$dbname = "team011";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(array("success" => false, "message" => "Connection failed: " . $conn->connect_error)));
}

// Escape user input for security
$email = $conn->real_escape_string($_POST['reset_email']);
$security_answer = $conn->real_escape_string($_POST['security_answer']);

// Prepare SQL statement to check if email exists and security question answer is correct
$stmt = $conn->prepare("SELECT user_email FROM Users_Details WHERE user_email = ? AND secure_ans = ?");
$stmt->bind_param("ss", $email, $security_answer);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if there is a matching record
if ($result->num_rows > 0) {
    // Store the user's email in a session variable
    $_SESSION['reset_email'] = $email;
    
    // Redirect user to the reset password page
    header("Location: resetPassword.php");
    exit; // Stop further execution
} else {
    // If no matching record found, redirect back to forgot password page with error message
    // $_SESSION['error_message'] = "Invalid email or security answer";
    echo "Invalid email or security answer";
    // header("Location: forgot_password.php");
    exit; // Stop further execution
}

// Close the prepared statement
$stmt->close();

// Close database connection
$conn->close();
?>
