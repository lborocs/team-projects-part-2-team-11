<?php
// Database connection details
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$servername = "localhost";
$dbname = "team011";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the project ID from the request
$projectId = $_GET['projectId'];

// Prepare SQL statement to fetch project name
$stmt = $conn->prepare("SELECT name FROM Project WHERE project_id = ?");
$stmt->bind_param("i", $projectId);
$stmt->execute();
$result = $stmt->get_result();

// Check if a row is found
if ($result->num_rows > 0) {
    // Fetch the project name
    $row = $result->fetch_assoc();
    $projectName = $row['name'];
    echo $projectName;
} else {
    // No project found with the given ID
    echo "Project not found";
}

// Close prepared statement and connection
$stmt->close();
$conn->close();
?>
