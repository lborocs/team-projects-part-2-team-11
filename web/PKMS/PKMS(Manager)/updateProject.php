<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
// Check if all required fields are received
$projectId = mysqli_real_escape_string($conn, $_POST['projectId']);
$projectName = mysqli_real_escape_string($conn, $_POST['projectName']);
$deadline = mysqli_real_escape_string($conn, $_POST['editDeadline']);
$teamLeader = mysqli_real_escape_string($conn, $_POST['teamLeader']);
$duration = mysqli_real_escape_string($conn, $_POST['editDuration']);
$teamMemberQuery = "SELECT user_email FROM Users_Details WHERE username = ?";

$stmt = $conn->prepare($teamMemberQuery);
$stmt->bind_param("s", $teamLeader);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the result row
    $row = $result->fetch_assoc();
    $teamLeader = $row['user_email']; // Assign the email to the team member variable
} else {
    echo json_encode(array("status" => "error", "message" => "Team Member not found"));
    exit; // Exit the script to prevent further execution
}

// Prepare and execute a SQL query to update project details
$sql = "UPDATE Project SET name = ?, deadline = ?, team_leader = ? , duration = ? WHERE project_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssii", $projectName, $deadline, $teamLeader, $duration, $projectId);
$stmt->execute();
$conn->commit();

if ($stmt->affected_rows > 0) {
    // Project details updated successfully
    $response = array('success' => true);
    echo json_encode($response);
} else {
    // Failed to update project details
    $response = array('success' => false, 'error' => 'Failed to update project details');
    echo json_encode($response);
}

// Close the statement
$stmt->close();

$conn->close();

?>