<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Function to update the user table with data from 'get_users.php'
function fetchTasksForProject($projectName, $conn) {
    $sql = "SELECT 
            IFNULL(u.username, '') AS assigned_user,
            COUNT(*) AS num_tasks, 
            GROUP_CONCAT(CONCAT(t.title, ' - ', t.status) SEPARATOR '<br>') AS task_details,
            GROUP_CONCAT(t.title) AS task_description,
            GROUP_CONCAT(t.status) AS status
            FROM Tasks t
            LEFT JOIN Users_Details u ON t.user_email = u.user_email
            INNER JOIN Project p ON t.project_id = p.project_id
            WHERE p.name = '$projectName'
            GROUP BY assigned_user
            ORDER BY assigned_user = '' DESC";

    $result = $conn->query($sql);
    $tasks = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }
    }

    return $tasks; //Return Fetched tasks
}

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

if (isset($_GET['projectName'])) {
    $projectName = $_GET['projectName'];

    // Fetch tasks for the selected project
    $tasks = fetchTasksForProject($projectName, $conn);

    // Output JSON representation of tasks
    echo json_encode($tasks);
} else {
    echo "Project name not provided";
}

// Close connection
mysqli_close($conn);
?>
