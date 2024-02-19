<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Database connection details
$servername = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Retrieve list of users and their roles
$sql = "SELECT user_email, role FROM Users_Details";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    // Output data of each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr><td>" . $row['user_email'] . "</td><td>" . $row['role'] . "</td></tr>";
    }
} else {
    echo "<tr><td colspan='2'>No users found</td></tr>";
}

// Close connection
mysqli_close($conn);
?>
