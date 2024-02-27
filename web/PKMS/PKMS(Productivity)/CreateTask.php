<?php
    //set up the connection to the data base
    $username = "team011";
    $password = "JAEWyfUXpzqank7scpWm";
    $servername = "localhost";
    $dbname = "team011";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    //collect necesssary 
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $taskTitle = $_POST['taskTitle'];
        $taskDescription = $_POST['taskDescription'];
        $taskDeadline = $_POST['taskDeadline'];
        $assignedTo = $_POST['assignedTo'];
        $Project_ID = $_POST['Project_ID'];
        $estimatedTime = $_POST['estimated_time'];

    } else {
        $taskTitle = '';
        $taskDescription = '';
        $taskDeadline = '';
        $assignedTo = '';
        $Project_ID = '';
        $estimatedTime = '';
    }

    //generate taskID
    $tasksQuery = "SELECT MAX(`task_id`) FROM `Tasks` 
    WHERE 1;";
    $allTasks = mysqli_query($conn, $tasksQuery);

    //inrement the highest task ID.
    if (mysqli_num_rows($allTasks) > 0){
        while ($row = mysqli_fetch_array($allTasks)){
            $taskID = intval($row[0]) + 1;
        }
    }

    //Do the query to create the new tasks
    $createTasksQuery = "INSERT INTO `Tasks` (`task_id`, `title`, `description`, `deadline`, `status`, `user_email`, `project_id`, `estimated_time`) 
    VALUES ('$taskID', '$taskTitle', '$taskDescription', '$taskDeadline', 'INCOMPLETE', '$assignedTo', '$Project_ID', '$estimatedTime');";
    mysqli_query($conn, $createTasksQuery);
?>