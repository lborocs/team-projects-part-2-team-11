<?php

// Set up the connection to the database
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$servername = "localhost";
$dbname = "team011";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

echo "Success";

// Process form data
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password1 = $_POST['password1']; // Store plaintext password
    $securityans = $_POST['securityAnswer'];

    // Check if the email already exists
    $checkEmailQuery = "SELECT * FROM Users_Details WHERE user_email = ?";
    $stmt = mysqli_prepare($conn, $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // Perform SQL update if email exists
        $updateQuery = "UPDATE Users_Details SET username=?, password=?, secure_ans=? WHERE user_email=?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $password1, $securityans, $email);

        if (mysqli_stmt_execute($stmt)) {
            echo "Update successful";
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        // Perform SQL insert if email does not exist
        $insertQuery = "INSERT INTO Users_Details (user_email, username, password, secure_ans) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmt, "ssss", $email, $username, $password1, $securityans);
        
        if (mysqli_stmt_execute($stmt)) {
            echo "Registration successful";
        } else {
            echo "Error inserting record: " . mysqli_error($conn);
        }
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>
