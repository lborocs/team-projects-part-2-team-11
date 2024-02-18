<?php
    try{
        //set up the connection to the data base
        $username = "team011";
        $password = "JAEWyfUXpzqank7scpWm";
        $servername = "localhost";
        $dbname = "team011";

        // Create connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            $taskTitle = $_POST['taskTitle'];
            $taskDescription = $_POST['taskDescription'];
            $taskDeadline = $_POST['taskDeadline'];
            $assignedTo = $_POST['assignedTo'];
            $Project_ID = $_POST['Project_ID'];

        } else {
            $taskTitle = '';
            $taskDescription = '';
            $taskDeadline = '';
            $assignedTo = '';
            $Project_ID = '';
        }

        //generate taskID
        $tasksQuery = "SELECT MAX(`task_id`) FROM `Tasks` 
        WHERE 1;";
        $allTasks = mysqli_query($conn, $tasksQuery);


        if (mysqli_num_rows($allTasks) > 0){
            while ($row = mysqli_fetch_array($allTasks)){
                $taskID = intval($row[0]) + 1;
            }
        }

        //get all tasks
        $createTasksQuery = "INSERT INTO `Tasks` (`task_id`, `title`, `description`, `deadline`, `status`, `user_email`, `project_id`) 
        VALUES ('$taskID', '$taskTitle', '$taskDescription', '$taskDeadline', 'INCOMPLETE', '$assignedTo', '$Project_ID');";
        mysqli_query($conn, $createTasksQuery);


    }catch(Exception $e){
        //catch whatever exception is thrown first and display it's message.
        echo "Error: ".$e->getMessage();
    }

?>