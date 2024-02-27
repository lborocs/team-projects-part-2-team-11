<?php
    //set up the connection to the data base
    $username = "team011";
    $password = "JAEWyfUXpzqank7scpWm";
    $servername = "localhost";
    $dbname = "team011";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    //collect all the necessary variables
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $title = $_POST['taskTitle'];
        $description = $_POST['taskDescription'];
        $deadline = $_POST['taskDeadline'];
        $status = $_POST['taskStatus'];
        $assignedTo = $_POST['assignedTo'];
        $Project_ID = $_POST['Project_ID'];
        $estimatedTime = $_POST['estimated_time'];

        if ($estimatedTime == "N/A"){
            $estimatedTime = 0;
        }

        $taskID = $_POST['Task_ID'];
        $update = $_POST['Update'];
    } else {
        $taskTitle = '';
        $taskDescription = '';
        $taskDeadline = '';
        $assignedTo = '';
        $Project_ID = '';
        $estimatedTime = '';

        $taskID = '';
        $update = '';
    }

    //if the task is to be updated complete run this section of the code
    if ($update == 'updateTask'){
        $query = "UPDATE `Tasks`
        SET `title` = '$title', `description` = '$description', `deadline` = '$deadline',`status` = '$status', `user_email` = '$assignedTo', `estimated_time` = '$estimatedTime'
        WHERE `task_id` = $taskID;";
        mysqli_query($conn, $query);

    //if status is just being changed run this code
    } elseif ($update == 'updateTaskStatus'){
        $query = "UPDATE `Tasks`
        SET `status` = '$status'
        WHERE `task_id` = $taskID;";
        mysqli_query($conn, $query);
    
        //if task is to be deleted run this code
    } elseif ($update == 'deleteTask') {
        $query = "DELETE FROM `Tasks`
        WHERE `task_id` = $taskID;";
        mysqli_query($conn, $query);
    }
?>