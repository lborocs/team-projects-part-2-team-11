<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// set up the connection to the database
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$servername = "localhost";
$dbname = "team011";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve email and new password from the form
$email = $_SESSION['reset_email'];
$newPassword = $_POST['newPassword'];

$hash = password_hash($newPassword,PASSWORD_DEFAULT);

// Update password in the database
$query = "UPDATE `Users_Details`
          SET `Users_Details`.`password` = '$hash'   
          WHERE user_email = '$email' ";
$result = mysqli_query($conn, $query); //Execute the update query

// Check if the update was successful
if ($result) {
    // Password updated successfully
    echo "<script>alert('Password updated successfully.');</script>";
    echo "<script>window.location.href = 'signin.php';</script>";
    exit;
} else {
    // Password update failed
    echo "<script>alert('Password update failed. Please try again.');</script>";
}
?>
