<?php
// Enable error reporting for visibility of errors during development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Retrieve email, topic ID, and post number from GET request
$email = $_GET['email'];
$topicid = $_GET['topic_ID'];
$postnum = $_GET['postnum'];

// Database connection parameters
$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Establish connection to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check if the database connection was successful
if (!$conn) {
    // If connection failed, return an error message in JSON format
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

// SQL query to check if a particular favorite exists
$sql = "SELECT * FROM Favourites WHERE user_email = ? AND Topic_ID = ? AND Postnum = ?";
// Prepare the SQL statement for execution
$stmt = $conn->prepare($sql);
// Check if the statement preparation was successful
if (!$stmt) {
    // If statement preparation failed, return an error message in JSON format
    die(json_encode(['error' => 'Error preparing statement: ' . $conn->error]));
}

// Bind the parameters (email, topic ID, post number) to the prepared statement
$stmt->bind_param("ssi", $email, $topicid, $postnum);
// Execute the prepared statement
$stmt->execute();
// Retrieve the result set from the executed statement
$result = $stmt->get_result();
// Check if any rows were returned, indicating the favorite exists
if ($result->num_rows > 0) {
    // If the favorite exists, return a status indicating it's a "heart" (i.e., liked)
    $response = ['status' => 'heart'];
} else {
    // If the favorite does not exist, return a status indicating it's "noheart" (i.e., not liked)
    $response = ['status' => 'noheart'];
}

// Close the prepared statement
$stmt->close();
// Close the database connection
$conn->close();

// Return the response in JSON format
echo json_encode($response);
?>
