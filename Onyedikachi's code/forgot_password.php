<?php
session_start(); // Start session if not already started

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
$email = $conn->real_escape_string($_POST['email']);

// Prepare SQL statement to check if email exists
$stmt = $conn->prepare("SELECT * FROM Users_Details WHERE user_email = ?");
$stmt->bind_param("s", $email);

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Check if the email exists in the database
if ($result->num_rows > 0) {
    // Generate a random reset token
    $reset_token = bin2hex(random_bytes(32));

    // Store the reset token in the database
    $update_stmt = $conn->prepare("UPDATE Users_Details SET reset_token = ? WHERE user_email = ?");
    $update_stmt->bind_param("ss", $reset_token, $email);
    $update_stmt->execute();

    // Send reset password email with a link containing the reset token
    $reset_link = "http://team011.sci-project.lboro.ac.uk/PKMS/PKMS_Complete/reset_password.php" . $reset_token; // Change this to your reset password page URL
    $to = $email;
    $subject = "Reset Your Password";
    $message = "To reset your password, please click the following link: $reset_link";
    $headers = "From: your@example.com" . "\r\n" .
               "Reply-To: your@example.com" . "\r\n" .
               "X-Mailer: PHP/" . phpversion();

    // Send the email
    mail($to, $subject, $message, $headers);

    // Respond with success message
    echo json_encode(array("success" => true, "message" => "Reset password link sent to your email"));
} else {
    // Email not found in the database
    echo json_encode(array("success" => false, "message" => "Email not found in the database"));
}

// Close the prepared statement
$stmt->close();

// Close database connection
$conn->close();
?>
