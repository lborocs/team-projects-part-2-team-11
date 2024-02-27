<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
    //set up the connection to the data base
    $username = "team011";
    $password = "JAEWyfUXpzqank7scpWm";
    $servername = "localhost";
    $dbname = "team011";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $ID = $_POST['Project_ID'];
        $filter_email = $_POST['Filter'];
        echo "<b>Project ID</b> :".$ID;
    } else {
        $ID = 0;
        $filter_email = '';
    }
    
    //get the user's username.
    $employeeQuery1 = "SELECT username 
    FROM `Users_Details` 
    WHERE user_email = '$filter_email';";
    $employee = mysqli_query($conn, $employeeQuery1);

    //se the filter value
    if (mysqli_num_rows($employee) > 0){
        //output data of each row
        while ($row = mysqli_fetch_array($employee)){
            $filter = $row[0];
        }
    } else {
        $filter_email = '%';
    }

    //get the project information
    $projectQuery = "SELECT *
    FROM Project
    WHERE project_id = '$ID'";
    $project = mysqli_query($conn, $projectQuery);

    if (mysqli_num_rows($project) > 0){
        while ($row = mysqli_fetch_array($project)){
            // echo "  <b>Title</b> : ".$row[1];
            echo "  <b>Deadline</b>: ".$row[2];
            $allocatedHours = $row[5];
            //echo "  <b>Allocated hours</b> : ".$row[5];
            echo "  <b>Project Status</b> : ".$row[6];
        }
    }

    echo "<br>";

    //total task project estimated time
    $totalEstimateQuery = "SELECT SUM(estimated_time)
    FROM Tasks
    WHERE project_id = '$ID'";
    $totalEstimate = mysqli_query($conn,$totalEstimateQuery);

    if(mysqli_num_rows($totalEstimate)>0){
        while ($row = mysqli_fetch_array($totalEstimate)){
            echo "  <b>Estimated Project Hours</b> : ".$row[0];
            echo "  <b>Allocated hours</b> : ".$allocatedHours;
            echo " <b>Differnece</b> : ".(intval($row[0])-intval($allocatedHours));
        }
    }


    echo '
    <div id = "Kanban" style = "display: flex; align-items: stretch;">
    <div id  = "unassignedTasks" style = "width : 100%">
    <h3>Unassigned</h3>';

        //get all tasks that are unassigned and incomplet form the database
        //list them here
        $query1 = "SELECT * FROM `Tasks` 
        WHERE project_id = '$ID' and user_email = '' and `status` = 'INCOMPLETE';";
        $column1 = mysqli_query($conn, $query1);

        if (mysqli_num_rows($column1) > 0){
            //output data of each row
            while ($row = mysqli_fetch_array($column1)){
                echo "<a href = '#Task:$row[0]' onclick = 'getTaskDetails($row[0])'> task : [".$row[0]."] -> ".$row[1]."</a>";
            }
        } else {
            echo "None";
        }

    echo '
    </div>

    <div id  = "incompleteTasks" style = "width : 100%">
    <h3>Incomplete</h3>';

        //get all tasks that are assigned and incomplete form the database
        //list them here
        $query2 = "SELECT * FROM `Tasks` 
        WHERE project_id = '$ID' and `status` = 'INCOMPLETE' and user_email LIKE '$filter_email' and user_email != '';";
        $column2 = mysqli_query($conn, $query2);

        if (mysqli_num_rows($column2) > 0){
            //output data of each row
            while ($row = mysqli_fetch_array($column2)){
                echo "<a href = '#Task:$row[0]' onclick = 'getTaskDetails($row[0])'> task : [".$row[0]."] -> ".$row[1]."</a>";
            }
        } else {
            echo "None";
        }

    echo '
    </div>

    <div id  = "completeTasks" style = "width: 100%">
    <h3>Complete</h3>';

        //get all tasks that are complete from the database
        $query3 = "SELECT * FROM `Tasks` 
        WHERE project_id = '$ID' and (user_email LIKE '$filter_email') and `status` = 'COMPLETE';";
        $column3 = mysqli_query($conn, $query3);

        if (mysqli_num_rows($column3) > 0){
            //output data of each row
            while ($row = mysqli_fetch_array($column3)){
                echo "<a href = '#Task:$row[0]' onclick = 'getTaskDetails($row[0])'> task : [".$row[0]."] -> ".$row[1]."</a>";
            }
        } else {
            echo "None";
        }
    echo '   
    </div>
</div>
    <div>';
        echo '<label for="Filter">Filter:</label>
        <select id="Filter" name="Filter" class="input-field" required>';
        if ($filter_email == '%'){
            echo '<option value="">Select User</option>';
        } else {
            echo '<option value="'.$filter_email.'">'.$filter.'</option>';
        }
        

        //get all team members on the project to be used for the filter.
        $UsersQuery = "SELECT DISTINCT `Tasks`.user_email, `Users_Details`.username 
        FROM `Tasks` INNER JOIN `Users_Details` ON `Users_Details`.`user_email` = `Tasks`.`user_email` 
        WHERE project_id = '$ID' and `Tasks`.user_email != '$filter_email';";
        $allUsers = mysqli_query($conn, $UsersQuery);

        if (mysqli_num_rows($allUsers) > 0){
            //output data of each row
            while ($row = mysqli_fetch_array($allUsers)){
                echo "<option value='$row[0]'>".$row[1]."</option>";
            }
        }
        echo '</select><br>';
echo '
</div>';

?>