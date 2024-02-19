<?php

// Enable error reporting for visibility during development
error_reporting(E_ALL);
ini_set("display_errors", 1);

// Retrieve email, topic ID, and post number from the query parameters
$email = $_GET['email'];
$topicid = $_GET['topic_ID'];
$postnum = $_GET['postnum'];

// Database configuration variables
$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Establish connection to the database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check if the database connection was successful
if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

// SQL query to check if the favorite already exists
$sql = "SELECT * FROM Favourites WHERE user_email = ? AND Topic_ID = ? AND Postnum = ?";
$stmt = $conn->prepare($sql);

// Check if the SQL statement was prepared correctly
if (!$stmt) {
    die(json_encode(['error' => 'Error preparing statement: ' . $conn->error]));
}

// Bind parameters to the prepared statement
$stmt->bind_param("ssi", $email, $topicid, $postnum);

// Execute the prepared statement and get the result
$stmt->execute();
$result = $stmt->get_result();

// Initialize an empty response array
$response = [];

// Check if the query returned any rows (i.e., the favorite exists)
if ($result->num_rows > 0) {
    // Prepare SQL to delete the existing favorite
    $sql2 = "DELETE FROM Favourites WHERE user_email = ? AND Topic_ID = ? AND Postnum = ?";
    $stmt2 = $conn->prepare($sql2);
    
    // Check if the delete statement was prepared correctly
    if (!$stmt2) {
        die(json_encode(['error' => 'Error preparing delete statement: ' . $conn->error]));
    }

    // Bind parameters and execute the delete statement
    $stmt2->bind_param("ssi", $email, $topicid, $postnum);
    if ($stmt2->execute()) {
        // Successfully removed, update the response status
        $response['status'] = 'removed';
    } else {
        // Failed to remove, include an error in the response
        $response['error'] = 'Failed to remove favorite';
    }
    $stmt2->close(); // Close the delete statement
} else {
    // Prepare SQL to add a new favorite
    $sql2 = "INSERT INTO Favourites (user_email, Topic_ID, Postnum) VALUES (?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    
    // Check if the insert statement was prepared correctly
    if (!$stmt2) {
        die(json_encode(['error' => 'Error preparing insert statement: ' . $conn->error]));
    }

    // Bind parameters and execute the insert statement
    $stmt2->bind_param("ssi", $email, $topicid, $postnum);
    if ($stmt2->execute()) {
        // Successfully added, update the response status
        $response['status'] = 'added';
    } else {
        // Failed to add, include an error in the response
        $response['error'] = 'Failed to add favorite';
    }
    $stmt2->close(); // Close the insert statement
}

$stmt->close(); // Close the initial select statement
$conn->close(); // Close the database connection

// Return the response as JSON
echo json_encode($response);
?>
