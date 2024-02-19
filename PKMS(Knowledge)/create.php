<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Retrieve data from POST request
$content = $_POST['content'];
$email = $_POST['email'];
$topic = $_POST['topic'];
$category = $_POST['category'];
$topicid = $_POST['topicid'];
$title = $_POST['title'];
$post_date = date('Y-m-d'); // Current date in 'YYYY-MM-DD' format for compatibility with SQL

// Database connection parameters
$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Establish a new database connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check if connection was successful
if (!$conn) {
    echo "UNSUCCESSFUL";
    die("Connection failed: " . mysqli_connect_error());
}

// SQL query to count existing posts for a given topic
$sql1 = "SELECT COUNT(*) FROM Posts WHERE topic = ?";
// Prepare the SQL statement for execution
$stmt1 = $conn->prepare($sql1);
if ($stmt1 === false) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}
// Bind the topic parameter to the prepared statement
$stmt1->bind_param("s", $topic);
// Execute the prepared statement
$stmt1->execute();

// Store the result of the query
$result1 = $stmt1->get_result();
// Fetch the count of posts from the result
$row = $result1->fetch_array();
$count = $row[0]; // Store the count in $count

// Calculate the new post number by adding one to the existing count
$newCount = $count + 1;

// Close the prepared statement
$stmt1->close();

// SQL query to insert a new post
$sql = "INSERT INTO Posts (Topic_ID, PostNo, content, date_updated, topic, user_email, title, category) VALUES (?,?, ?, ?, ?, ?, ?, ?)";

// Prepare the SQL statement for execution
$stmt = $conn->prepare($sql);

// Check if the statement was prepared correctly
if ($stmt === false) {
    echo "Error preparing statement: " . $conn->error;
    exit;
}

// Bind parameters to the prepared statement (type specification: 's' for string, 'i' for integer)
$stmt->bind_param("sissssss", $topicid, $newCount, $content, $post_date, $topic, $email, $title, $category);

// Execute the prepared statement and check for success
if ($stmt->execute()) {
    echo "New record created successfully";
} else {
    echo "Error: " . $stmt->error;
}

// Close the statement and connection to clean up
$stmt->close();
$conn->close();
?>
