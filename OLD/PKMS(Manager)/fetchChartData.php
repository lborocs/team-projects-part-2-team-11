<?php
// Retrieve the project name from the request
$projectId = $_GET['projectId'];

// Database connection
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

// Prepare and execute a SQL query to check if any tasks exist for the specified project
$checkTasksQuery = "SELECT COUNT(*) AS taskCount FROM Tasks WHERE project_id = ?";
$stmtCheckTasks = $conn->prepare($checkTasksQuery);
$stmtCheckTasks->bind_param("s", $projectId);
$stmtCheckTasks->execute();
$resultCheckTasks = $stmtCheckTasks->get_result();
$rowCheckTasks = $resultCheckTasks->fetch_assoc();
$taskCount = $rowCheckTasks['taskCount'];

// Prepare the data to send back to the client-side JavaScript
$data = array();


if ($taskCount > 0) {
    // Prepare and execute a SQL query to get the total number of tasks for the specified project
    $totalTasksQuery = "SELECT COUNT(*) AS totalTasks FROM Tasks WHERE project_id = ?";
    $stmtTotalTasks = $conn->prepare($totalTasksQuery);
    $stmtTotalTasks->bind_param("s", $projectId);
    $stmtTotalTasks->execute();
    $resultTotalTasks = $stmtTotalTasks->get_result();
    $rowTotalTasks = $resultTotalTasks->fetch_assoc();
    $totalTasks = $rowTotalTasks['totalTasks'];

    // Prepare and execute a SQL query to get the number of completed tasks for the specified project
    $completedTasksQuery = "SELECT COUNT(*) AS completedTasks FROM Tasks WHERE project_id = ? AND status = 'COMPLETE'";
    $stmtCompletedTasks = $conn->prepare($completedTasksQuery);
    $stmtCompletedTasks->bind_param("s", $projectId);
    $stmtCompletedTasks->execute();
    $resultCompletedTasks = $stmtCompletedTasks->get_result();
    $rowCompletedTasks = $resultCompletedTasks->fetch_assoc();
    $completedTasks = $rowCompletedTasks['completedTasks'];

    // Calculate the percentage of completed tasks
    $percentageCompleted = ($completedTasks / $totalTasks) * 100;

    // Prepare the data to send back to the client-side JavaScript
    $data = array(
        'totalTasks' => $totalTasks,
        'completedTasks' => $completedTasks,
        'percentageCompleted' => $percentageCompleted
    );

} else {
    // If no tasks exist, set the data array with a message indicating no tasks
    $data = array(
        'message' => 'No tasks found for the project'
    );
}


// Send the data as JSON response
header('Content-Type: application/json');
echo json_encode($data);


// Close your database connection
$stmtCheckTasks->close();
if ($taskCount > 0) {
    $stmtTotalTasks->close();
    $stmtCompletedTasks->close();
}
// Close your database connection
$conn->close();
?>
