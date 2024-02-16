<?php
    //set up the connection to the data base
    $username = "team011";
    $password = "JAEWyfUXpzqank7scpWm";
    $servername = "localhost";
    $dbname = "team011";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);


    // echo "HERE(4)!";
    // echo $_POST['Project_ID'];

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $ID = $_POST['Project_ID'];
        $user = $_POST['User'];
        echo $ID;
    } else {
        $ID = 0;
    }
    
    //get all tasks
    $tasksQuery = "SELECT * FROM `Tasks` 
    WHERE project_id = '$ID' and user_email = '$user';";
    $allTasks = mysqli_query($conn, $tasksQuery);

    if (mysqli_num_rows($allTasks) > 0){
        //output data of each row
        while ($row = mysqli_fetch_array($allTasks)){
            echo "<a href = '#Task:$row[0]' onclick = 'getTaskDetails($row[0])'> task : [".$row[0]."] -> ".$row[1]."</a>";
        }
    }
?>