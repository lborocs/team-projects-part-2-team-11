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

// Check if the project ID is received
if (isset($_POST['projectId'])) {
    // Fetch project details from the database based on the project ID
    $projectId = $_POST['projectId'];
    $sql = "SELECT Project.*, Users_Details.username AS username 
    FROM Project 
    INNER JOIN Users_Details ON Project.team_leader = Users_Details.user_email 
    WHERE Project.project_id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $projectId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch project details
        $row = $result->fetch_assoc();
        $projectName = $row['name'];
        $deadline = $row['deadline'];
        $duration = $row['duration'];
        $teamLeader = $row['username'];

        // Prepare the response data
        $responseData = array(
            'projectName' => $projectName,
            'deadline' => $deadline,
            'duration' => $duration,
            'teamLeader' => $teamLeader
        );

        // Send the project details as JSON response
        header('Content-Type: application/json');
        echo json_encode($responseData);
    } else {
        // No project found with the given ID
        echo "Project not found";
    }

    // Close the database connection and exit
    $stmt->close();
} 

// Close the database connection
$conn->close();
?>