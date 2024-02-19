<?php

// Enable detailed error reporting for debugging
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Retrieve POST data sent to the script
$topicID = $_POST['topic_ID']; // The ID of the topic being updated
$postnum = $_POST['postnum']; // The number of the post within the topic
$title = $_POST['title']; // The new title for the post
$content = $_POST['content']; // The new content for the post
$post_date = date('Y-m-d'); // The current date, to record when the post was updated

// Database connection parameters
$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Attempt to establish a connection to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check if the database connection was successful
if (!$conn) {
    // If the connection failed, return a JSON encoded error message and exit the script
    echo json_encode(['status' => 'error', 'message' => 'Connection failed: ' . mysqli_connect_error()]);
    exit;
}

// SQL query to update a post with new title, content, and update date
$sql = "UPDATE Posts SET title = ?, content = ?, date_updated = ? WHERE Topic_ID = ? AND PostNo = ?;";

// Prepare the SQL statement for execution to prevent SQL injection
$stmt = $conn->prepare($sql);

// Check if the statement was prepared correctly
if (!$stmt) {
    // If statement preparation fails, return a JSON encoded error message and exit the script
    echo json_encode(['status' => 'error', 'message' => "Error preparing statement: " . $conn->error]);
    exit;
}

// Bind the input parameters to the prepared statement
$stmt->bind_param("ssssi", $title, $content, $post_date, $topicID, $postnum);

// Execute the prepared statement
if ($stmt->execute()) {
    // If execution was successful, return a success message
    echo json_encode(['status' => 'success', 'message' => 'Record updated successfully']);
} else {
    // If execution failed, return an error message
    echo json_encode(['status' => 'failed', 'message' => "Error executing statement: " . $stmt->error]);
}

// Close the prepared statement and the database connection
$stmt->close();
$conn->close();

?>
