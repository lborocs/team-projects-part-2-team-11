<?php
// Retrieve the project ID from the request
$data = json_decode(file_get_contents("php://input"), true);
$projectId = $data['projectId'];

// set up the connection to the database
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

// Example SQL queries (you'll need to adjust them based on your database schema)
$sqlDeleteProject = "DELETE FROM Project WHERE project_id = ?";
$sqlDeleteTasks = "DELETE FROM Tasks WHERE project_id = ?";

// Prepare and bind the SQL statements
$stmtDeleteProject = $conn->prepare($sqlDeleteProject);
$stmtDeleteTasks = $conn->prepare($sqlDeleteTasks);

// Bind parameters
$stmtDeleteProject->bind_param("i", $projectId);
$stmtDeleteTasks->bind_param("i", $projectId);

// Execute the SQL queries
$stmtDeleteProject->execute();
$stmtDeleteTasks->execute();

// Check for errors and send appropriate responses
if ($stmtDeleteProject->error) {
    http_response_code(500);
    echo "Error deleting project: " . $stmtDeleteProject->error;
} else {
    echo "Project deleted successfully";
}

// Close statements and connection
$stmtDeleteProject->close();
$stmtDeleteTasks->close();
$conn->close();
?>
