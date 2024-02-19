<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the required POST data (user_email and can_write) is present
if (isset($_POST['user_email']) && isset($_POST['can_write'])) {
    $email = $_POST['user_email']; // Retrieve the user's email from POST data
    $canWrite = (int)$_POST['can_write']; // Retrieve the new write permission state and cast it to an integer

    // Prepare an SQL statement for updating the user's write permission
    $stmt = $conn->prepare("UPDATE Users_Details SET can_write = ? WHERE user_email = ?");
    if ($stmt === false) {
        die("Failed to prepare statement: " . $conn->error);
    }

    
    $stmt->bind_param("is", $canWrite, $email);

    
    if ($stmt->execute()) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the connection
$conn->close();

?>

