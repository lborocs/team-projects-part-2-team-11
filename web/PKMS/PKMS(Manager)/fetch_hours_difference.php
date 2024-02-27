<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Fetch the difference in hours from the database
if (isset($_GET['projectName'])) {
    $projectName = $_GET['projectName'];

    // Set up the database connection
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

    // SQL statement to fetch set project hours
    $sqlSetProjectHours = "SELECT duration FROM Project WHERE name = '$projectName'";
    $setProjectHoursResult = $conn->query($sqlSetProjectHours);
    $setProjectHours = 0;

    if ($setProjectHoursResult->num_rows > 0) {
        $row = $setProjectHoursResult->fetch_assoc();
        $setProjectHours = $row['duration'];
    }

    // SQL statement to fetch total allocated task hours for the project
    $sqlTotalTaskHours = "SELECT SUM(t.estimated_time) AS total_task_hours 
                          FROM Tasks t 
                          JOIN Project p ON t.project_id = p.project_id 
                          WHERE p.name = '$projectName'";
    $totalTaskHoursResult = $conn->query($sqlTotalTaskHours);
    $totalTaskHours = 0;

    if ($totalTaskHoursResult->num_rows > 0) {
        $row = $totalTaskHoursResult->fetch_assoc();
        $totalTaskHours = $row['total_task_hours'];
    }

    // Calculate the difference between set project hours and total allocated task hours
    $difference = $setProjectHours - $totalTaskHours;

    // Output the difference in hours

    // Output the difference in hours
// echo "Estimated Project Hours: $setProjectHours ";
// echo "    Total Allocated Task Hours: $totalTaskHours ";
// echo "    Difference: $difference";
// Output the difference in hours
echo "Estimated Project Hours: " . ($setProjectHours !== null ? $setProjectHours : 0) . " ";
echo "Total Allocated Task Hours: " . ($totalTaskHours !== null ? $totalTaskHours : 0) . " ";
echo "Difference: " . ($difference !== null ? $difference : 0);


    // Close connection
    mysqli_close($conn);
} else {
    echo "Project name not provided";
}
?>
