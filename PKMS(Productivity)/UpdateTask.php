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
            $title = $_POST['taskTitle'];
            $description = $_POST['taskDescription'];
            $deadline = $_POST['taskDeadline'];
            $status = $_POST['taskStatus'];
            $assignedTo = $_POST['assignedTo'];
            $Project_ID = $_POST['Project_ID'];

            $taskID = $_POST['Task_ID'];
            $update = $_POST['Update'];

            echo $taskID;
        } else {
            $taskTitle = '';
            $taskDescription = '';
            $taskDeadline = '';
            $assignedTo = '';
            $Project_ID = '';

            $taskID = '';
            $update = '';
        }

        echo '<p>updateing = '.$update.'</p><br>';
        echo '<p>status = '.$status.'</p><br>';
        echo '<p>email = '.$assignedTo.'</p><br>';

        if ($update == 'updateTask'){
            echo '<p>ALL IS RUNNING</p>';
            $query = "UPDATE `Tasks`
            SET `title` = '$title', `description` = '$description', `deadline` = '$deadline',`status` = '$status', `user_email` = '$assignedTo'
            WHERE `task_id` = $taskID;";
            mysqli_query($conn, $query);

        } elseif ($update == 'updateTaskStatus'){
            echo '<p>Status IS RUNNING</p>';
            $query = "UPDATE `Tasks`
            SET `status` = '$status'
            WHERE `task_id` = $taskID;";
            mysqli_query($conn, $query);

        } elseif ($update == 'deleteTask') {
            $query = "DELETE FROM `Tasks`
            WHERE `task_id` = $taskID;";
            mysqli_query($conn, $query);
        }


    }catch(Exception $e){
        //catch whatever exception is thrown first and display it's message.
        echo "Error: ".$e->getMessage();
    }

    
?>