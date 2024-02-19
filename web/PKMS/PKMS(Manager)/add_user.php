<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user_email and role from the form
    $user_email = $_POST['user_email'];
    $role = $_POST['role'];

    // Connect to your database
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

    // Insert the new user into the database
    $sql = "INSERT INTO Users_Details (user_email, role) VALUES ('$user_email', '$role')";
    if ($conn->query($sql) === TRUE) {
        // Retrieve updated list of users
        $sql = "SELECT user_email FROM Users_Details";
        $result = $conn->query($sql);
        $users = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $users[] = $row['user_email'];
            }
        }
        echo json_encode($users); // Return updated list of users as JSON
    } else {
        echo "Error adding user: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
}
?>

