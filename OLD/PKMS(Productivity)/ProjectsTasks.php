<?php
    try{
        if ($_SERVER["REQUEST_METHOD"] == "POST"){
            echo "HERE(4)!!!";
            echo $_POST['Project_ID'];
            $ID = $_POST['Project_ID'];
            echo $ID;
        } else {
            $ID = 0;
        }
        
        //get all tasks
        $tasksQuery = "SELECT * FROM `Tasks` 
        WHERE project_id = '$ID';";
        $allTasks = mysqli_query($conn, $tasksQuery);

        if (mysqli_num_rows($allTasks) > 0){
            //output data of each row
            while ($row = mysqli_fetch_array($allTasks)){
                echo "<a href = '#Example'> task : [".$row[0]."] -> ".$row[1]."</a>";
                //$possibleCountries = $row[0];
            }
        }

    }catch(Exception $e){
        //catch whatever exception is thrown first and display it's message.
        echo "Error: ".$e->getMessage();
    }
?>