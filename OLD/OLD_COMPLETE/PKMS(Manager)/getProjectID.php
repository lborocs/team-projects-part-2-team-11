<?php
// set up the connection to the database
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$servername = "localhost";
$dbname = "team011";


// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Get the project name from the request
$projectName = $_GET['projectName'];

// Prepare SQL statement to fetch project ID
$stmt = $conn->prepare("SELECT project_id FROM Project WHERE name = ?");
$stmt->bind_param("s", $projectName);
$stmt->execute();
$result = $stmt->get_result();

// Check if a row is found
if ($result->num_rows > 0) {
    // Fetch the project ID
    $row = $result->fetch_assoc();
    $projectId = $row['project_id'];
    echo $projectId;
} else {
    // No project found with the given name
    echo "Project not found";
}

// Close prepared statement and connection
$stmt->close();
mysqli_close($conn);
?>
