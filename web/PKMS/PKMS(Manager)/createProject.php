<?php
session_start();

// Check if session data is set
if(isset($_SESSION['email'])) {
    $User1 = $_SESSION['email'];
} 

$User = $_SESSION['email'];
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection details
$servername = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    // Log a message indicating successful connection
    error_log("Connected to the database successfully!");
}

// Retrieve the form data sent via AJAX
$data = json_decode(file_get_contents('php://input'), true);

// Retrieve the form data sent via POST
$projectName = $_POST['projectName'];
$projectDeadline = $_POST['projectDeadline'];

// $manager = $_POST['User'];
$manager=$User;

$teamLeader = $_POST['teamLeader'];

$teamLeaderQuery = "SELECT user_email FROM Users_Details WHERE username = ?";

$stmt = $conn->prepare($teamLeaderQuery);
$stmt->bind_param("s", $teamLeader);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the result row
    $row = $result->fetch_assoc();
    $teamLeader = $row['user_email']; 
} else {
    echo json_encode(array("status" => "error", "message" => "Team leader not found"));
    exit; // Exit the script to prevent further execution
}

// Generate projectID
$projectsQuery = "SELECT MAX(project_id) AS max_project_id FROM Project;";
$allProjects=mysqli_query($conn, $projectsQuery);

$maxProjectID = 101; // Assuming the initial project ID starts from 100
if (mysqli_num_rows($allProjects)>0){
    $row=mysqli_fetch_array($allProjects);
    $maxProjectID = $row['max_project_id']; // Get the highest project ID from the result
    $maxProjectID++; // Increment the highest project ID by 1 to get the next available project ID
} else {
    $maxProjectID++;
}


// Prepare and execute SQL statement with prepared statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO Project (project_id, name, deadline, manager, team_leader, duration, project_status) VALUES (?, ?, ?, ?, ?, NULL, 'INCOMPLETE')");
$stmt->bind_param("issss", $maxProjectID, $projectName, $projectDeadline, $manager, $teamLeader);
$stmt->execute();

// Check if insertion was successful
if ($stmt->affected_rows > 0) {
    echo json_encode(array("status" => "success", "message" => "Project created successfully"));
} else {
    // If error, return an error message
    echo json_encode(array("status" => "error", "message" => "Error creating project"));
}
// Close prepared statement
$stmt->close();

// Close connection
mysqli_close($conn);

?>

