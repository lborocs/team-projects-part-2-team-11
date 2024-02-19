<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Fetch project deadline from the database
    if (isset($_GET['projectName'])) {
        $projectName = $_GET['projectName'];

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

        // Query to fetch project deadline
        $sql = "SELECT deadline FROM Project WHERE name='$projectName'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $deadline = $row['deadline'];
            echo $deadline; // Return the deadline
        } else {
            echo "Deadline not found";
        }

        // Close connection
        mysqli_close($conn);
    } else {
        echo "Project name not provided";
    }
?>
