<?php

// Enable detailed error reporting
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Retrieve POST data
$topicid = $_POST['topic_ID'];
$postnum = $_POST['postnum'];

// Database connection parameters
$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Attempt to establish a connection to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check connection success
if (!$conn) {
    // If connection fails, return a JSON encoded error message and exit
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()]);
    exit;
}

// SQL statement to delete a specific post
$sql = "DELETE FROM Posts WHERE Topic_ID = ? AND PostNo = ?";
// Prepare the SQL statement for execution
$stmt = $conn->prepare($sql);

// Check if the statement was prepared correctly
if (!$stmt) {
    // If statement preparation fails, return a JSON encoded error message and exit
    echo json_encode(['status' => 'error', 'message' => 'Error preparing statement: ' . $conn->error]);
    exit;
}

// Bind the input parameters (topic ID and post number) to the prepared statement
$stmt->bind_param("si", $topicid, $postnum);

// Execute the prepared statement
if ($stmt->execute()) {
    // After execution, check if any rows were affected (i.e., if a post was actually deleted)
    if ($stmt->affected_rows > 0) {
        // If one or more rows were affected, return a success message
        echo json_encode(['status' => 'success']);
    } else {
        // If no rows were affected, return an error message (post not found or already deleted)
        echo json_encode(['status' => 'error']);
    }
} else {
    // If statement execution fails, return a JSON encoded error message
    echo json_encode(['status' => 'error']);
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();

?>
