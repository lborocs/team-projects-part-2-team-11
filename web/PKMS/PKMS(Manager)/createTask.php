<?php
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

$taskName = $_POST['taskName'];
$taskDescription = $_POST['taskDescription'];
$taskDeadline = $_POST['taskDeadline'];
$teamMember = $_POST['teamMember'];
$Project_ID = $_POST['Project_ID'];
$taskEstimatedTime= $_POST['taskEstimatedTime'];


$teamMemberQuery = "SELECT user_email FROM Users_Details WHERE username = ?";

$stmt = $conn->prepare($teamMemberQuery);
$stmt->bind_param("s", $teamMember);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the result row
    $row = $result->fetch_assoc();
    $teamMember = $row['user_email']; // Assign the email to the team member variable
} else {
    // echo json_encode(array("status" => "error", "message" => "Team Member not found"));
    // exit; // Exit the script to prevent further execution

    $teamMember = '';
}

//Generate taskID
$tasksQuery = "SELECT MAX(task_id) AS max_task_id FROM Tasks;";
$allTasks=mysqli_query($conn, $tasksQuery);

$maxTaskID = 1; // Assuming the initial project ID starts from 100
if (mysqli_num_rows($allTasks)>0){
    $row=mysqli_fetch_array($allTasks);
    $maxTaskID = $row['max_task_id'];
    $maxTaskID=$maxTaskID+1; 

} else {
    $maxTaskID=$maxTaskID+1;
}

// Prepare and execute SQL statement with prepared statement to prevent SQL injection
$stmt = $conn->prepare("INSERT INTO Tasks (task_id, title, description, deadline, status, user_email, project_id,estimated_time) VALUES (?,?, ?, ?,'INCOMPLETE',?,?,?)");
$stmt->bind_param("isssssi",$maxTaskID,$taskName, $taskDescription, $taskDeadline, $teamMember, $Project_ID,$taskEstimatedTime);

$stmt->execute();

// Check if insertion was successful
if ($stmt->affected_rows > 0) {
    echo json_encode(array("status" => "success", "message" => "Task created successfully"));
} else {
    // If error, return an error message
    echo json_encode(array("status" => "error", "message" => "Error creating Task"));
}
// Close prepared statement
$stmt->close();

// Close connection
mysqli_close($conn);
?>