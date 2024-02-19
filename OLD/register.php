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

// echo "Success";

// Process form data
if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password1 = $_POST['password1']; // Store plaintext password
    $securityans = $_POST['securityAnswer'];

    $plaintext_password = $password1;
    $hash = password_hash($plaintext_password,PASSWORD_DEFAULT);

    // Check if the email already exists
    $checkEmailQuery = "SELECT * FROM Users_Details WHERE user_email = ?";
    $stmt = mysqli_prepare($conn, $checkEmailQuery);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        // If email exists, perform SQL update
        $updateQuery = "UPDATE Users_Details SET username=?, password=?, secure_ans=? WHERE user_email=?";
        $stmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmt, "ssss", $username, $hash, $securityans, $email);

        if (mysqli_stmt_execute($stmt)) {
            echo '<script>alert("Registration successful!");</script>';
            header("Location: signin.php"); //after a successful login, it redirects you to the login page
            exit();
        } else {
            echo "Error updating record: " . mysqli_error($conn);
        }
    } else {
        // If email does not exist, deny registration
        echo '<script>alert("Email not found in datbase, Registration denied");</script>';
        // header("Location: registration.php");
    }

    // Close the statement
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>