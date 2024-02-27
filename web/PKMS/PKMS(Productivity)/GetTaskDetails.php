<?php
    //set up the connection to the data base
    $username = "team011";
    $password = "JAEWyfUXpzqank7scpWm";
    $servername = "localhost";
    $dbname = "team011";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    //set the necessary variable
    if ($_SERVER['REQUEST_METHOD'] == "POST"){
        $taskID = $_POST['Task_ID'];
        $user = $_POST['User'];
        $permission = $_POST['Permission'];
        $deletable = $_POST['Deletable'];
    } else {
        $user = '';
        $taskID = '';
        $permission = '';
        $deletable = '';
    }

   

    //get the tasks information
    $TaskQuery = "SELECT *
    FROM `Tasks` 
    WHERE task_id = '$taskID';";
    $TaskInfo = mysqli_query($conn, $TaskQuery);

    if (mysqli_num_rows($TaskInfo) > 0){
        //output data of each row
        while ($row = mysqli_fetch_array($TaskInfo)){
            $projectID = $row[6];
            $email = $row[5];
            if ($row[7] == 0){
                $row[7] = "N/A";
            }

            // if th epermistions passed in are true or the tasks ID is 
            if (($permission == 'true') || ($projectID == '0')){
                //grant full access to task changes
                echo "<h3>[".$row[0]."] : $row[1]</h3>";
            
                if ($permission == 'true'){
                    echo '<p align = "right"><i><b>(Editable)</b></i></p><br>';
                    echo "<p><b>Project</b> : $row[6] </p><br>";
                    
                    echo '<label for="assigned">Assigned To:</label>
                    <select id="assigned" name="assigned" class="input-field" required>';
                        //get the assigned user's username.
                        $employeeQuery1 = "SELECT username 
                        FROM `Users_Details` 
                        WHERE user_email = '$row[5]';";
                        $employee = mysqli_query($conn, $employeeQuery1);

                        if (mysqli_num_rows($employee) > 0){
                            //output data of each row
                            while ($row3 = mysqli_fetch_array($employee)){
                                echo "<option value='$row[5]'>".$row3[0]."</option>";
                            }
                            echo "<option value=''>"."Unassign"."</option>";
                        } else {
                            echo "<option value=''>"."Unassigned"."</option>";
                        }

                        //get all the users inside that are not teh already assigned user.
                        $employeeQuery = "SELECT user_email, username 
                        FROM `Users_Details` 
                        WHERE role = 'Employee' and username IS NOT NULL and user_email != '$row[5]';";
                        $allEmployees = mysqli_query($conn, $employeeQuery);

                        if (mysqli_num_rows($allEmployees) > 0){
                            //output data of each row
                            while ($row2 = mysqli_fetch_array($allEmployees)){
                                echo "<option value='$row2[0]'>".$row2[1]."</option>";
                            }
                        }
                    echo '</select><br><br>'; 
                } else {
                    echo '<p align = "right"><i><b>(Editable)</b></i></p><br>';
                    echo "<p><b>Project</b> : Personal </p><br>";

                    echo '<input id = "assigned" name = "assigned" value = "'.$row[5].'" type="hidden">';
                }

                //code for the title of the task
                echo '<label for="Title">Task Title:</label>
                    <input type="text" id="Title" name="Title" class="input-field" value = "'.$row[1].'" required><br>';
                
                //for the description of the task
                echo "<h4>Description:</h4>";
                echo "<textarea id='Description' name='Description' class='input-field' row = '4' cols ='50' style = 'height: 105px; width: 460px'>$row[2]</textarea><br>";
                
                //for the deadline fo the tasks
                echo "<label for='Deadline'>Task Deadline (YYYY-MM-DD):</label>";
                echo "<input type='text' id='Deadline' name='Deadline' class='input-field ' value = '$row[3]' required><br><br>";

                //for the estimated time for the tasks
                echo "<label for='estimated'>Task estimated Time</label>";
                echo "<input type='text' id='estimated' name='estimated' class='input-field ' value = '$row[7]' required><br><br>";

                //status change section:
                echo '<label for="Status">Status:</label>
                <select id="Status" name="Status" class="input-field" required>
                    <option value="'.$row[4].'">'.$row[4].'</option>';

                    if ($row[4] == 'INCOMPLETE'){
                        echo '"<option value="COMPLETE">COMPLETE</option>"';
                    } else {
                        echo '"<option value="INCOMPLETE">INCOMPLETE</option>"';
                    }
                    
                echo '</select><br>';

                //the update button code
                echo "<div style = 'Display : flex'>";
                echo "<button id = 'updateTask' type = 'submit' class='submit-button' onclick = 'updateTask($row[0],this.id)' >Update Task</button><br>";

                //if the deletavle flag is true allow the user to be able to delete task or if the task is complete.
                if (($deletable == 'true') || ($row[4] == 'COMPLETE')){
                    echo "<button id = 'deleteTask' class='submit-button' onclick = 'updateTask($row[0],this.id)' >Delete Task</button>";
                }
                echo "</div>";

            } else{
                //only allow them to change task status
                echo "<h3>[".$row[0]."] : $row[1]</h3>";

                echo "<pre><p><b>Project</b> : $row[6]</p><br>";
        
                //task description code.
                echo "<h4>Description:</h4>";
                echo "<textarea class='input-field' row = '4' cols ='50' style = 'height: 105px; width: 460px' readonly>$row[2]</textarea><br>";
                
                //task deadline code.
                echo "<label for='Deadline'>Task Deadline (YYYY-MM-DD):</label>";
                echo "<input id='Deadline' name='Deadline' type='text' class='input-field' value = '$row[3]' readonly><br>";

                //for the estimated time for the tasks
                echo "<label for='estimated'>Task estimated Time</label>";
                echo "<input type='text' id='estimated' name='estimated' class='input-field ' value = '$row[7]' readonly><br><br>";

                //the status section og the task detail
                echo '<label for="Status">Status:</label>
                <select id="Status" name="Status" class="input-field" required>
                    "<option value="'.$row[4].'">'.$row[4].'</option>"';

                    if ($row[4] == 'INCOMPLETE'){
                        echo '"<option value="COMPLETE">COMPLETE</option>"';
                    } else {
                        echo '"<option value="INCOMPLETE">INCOMPLETE</option>"';
                    }
                echo '</select><br>';

                //the task update button.
                echo "<button id = 'updateTaskStatus' type = 'submit' class='submit-button' onclick = 'updateTask($row[0],this.id)' >Update Status</button>";
            }
        }
    }



?>