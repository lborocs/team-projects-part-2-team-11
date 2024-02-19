<?php
// Retrieve the JSON data from the request body
$data = json_decode(file_get_contents("php://input"));

// Check if the required parameters are present
if (isset($data->projectId) && isset($data->status)) {
    // Extract the project ID and status from the JSON data
    $projectId = $data->projectId;
    $project_status = $data->status; // Use 'status' instead of 'project_status'

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

    // Prepare and execute a SQL query to update the project status
    $sql = "UPDATE Project SET project_status = ? WHERE project_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $project_status, $projectId);
    $stmt->execute();

    // Check if the update was successful
    if ($stmt->affected_rows > 0) {
        echo "Project status updated successfully";
    } else {
        echo "Failed to update project status";
    }

    // Close your database connection
    $stmt->close();
    $conn->close();
} else {
    echo "Missing parameters";
}
?>
