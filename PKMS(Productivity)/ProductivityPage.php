<!DOCTYPE html>
<html style="background-color:FFFFFF">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="ManagerDraft.css">
    <title> PKMS DashBoard</title>
</head>
<body>

    <?php
        //set up the connection to the data base
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

        if ($_SERVER['REQUEST_METHOD'] == "POST"){
            $User = $_POST['$User'];
        } else {
            $User = "";
        }
    ?>

    <!--PRODUCTIVITY PAGES-->
    <div id="productivitypage">
        <!-- ------------------------------------------Side bar--------------------------------------------------------- -->
        <!-- code for the side bar -->
        <div class="sidenav">
            <!-- my tasks sections section 1 -->
            <div>
            <h3>My Tasks</h3>
            <a id="CurrentTasksB" href="#MTasks">My Tasks</a>
            <a id="ArchivedTasksB" href="#ATasks">Archived Tasks</a>
            </div>
        
            <!-- my projects sections section 2 -->
            <div style="height: 30%;">
            <h3>My Projects</h3>
            <!-- Collect all the project that the user is in and display the project number in their side bar -->
    
            <?php
                try{
                    $User = 'frederick.umah@makeitall.org.uk';
                    
                    //get all tasks
                    $projQuery = "SELECT DISTINCT `Tasks`.`project_id`,  `Project`.`name`
                    FROM `Tasks` JOIN `Project` ON `Tasks`.`project_id` = `Project`.`project_id`
                    WHERE user_email = '$User' and `Tasks`.`project_id` != '0'
                    ORDER BY `Tasks`.`project_id` ASC;";
                    $allProjs = mysqli_query($conn, $projQuery);

        
                    if (mysqli_num_rows($allProjs) > 0){
                        //output data of each row
                        while ($row = mysqli_fetch_array($allProjs)){
                            $ProjectID = $row[0];
                            echo "<a id='$ProjectID' class = 'projectB' href='#Project:$ProjectID' onclick = 'getProjectTasks(this.id)'> ".$row[1]."</a>";
                        }
                    }
        
                }catch(Exception $e){
                    //catch whatever exception is thrown first and display it's message.
                    echo "Error: ".$e->getMessage();
                }
            ?>
            </div>

            <!-- my projects sections section 2 -->
            <div style="height: 30%;">
            <h3>Projects I Lead</h3>
            <!-- Collect all the project that the user is in and display the project number in their side bar -->
    
            <?php
                try{
                    $User = 'frederick.umah@makeitall.org.uk';
                    
                    //get all tasks
                    $projQuery = "SELECT project_id, `name`
                    FROM `Project` 
                    WHERE team_leader = '$User'
                    ORDER BY project_id ASC;";
                    $allProjs = mysqli_query($conn, $projQuery);

        
                    if (mysqli_num_rows($allProjs) > 0){
                        //output data of each row
                        while ($row = mysqli_fetch_array($allProjs)){
                            $ProjectID = $row[0];
                            echo "<a id='$ProjectID' class = 'projectB' href='#Project:$ProjectID' onclick = 'getProjectTasks_Leader(this.id)'> ".$row[1]."</a>";
                        }
                    }
        
                }catch(Exception $e){
                    //catch whatever exception is thrown first and display it's message.
                    echo "Error: ".$e->getMessage();
                }
            ?>
            </div>
        </div>

       <!-- ---------------------------------------------all the possible contents in the main------------------------------------------------------ -->

        <div id = "CurTasks", class = "main">
            <!--This will be the place where current task will be displayed.-->
            <h3>Current Tasks</h3>
            <div id = "listOfTasks">
                <?php
                    try{
                        $User = 'frederick.umah@makeitall.org.uk';
                        
                        //get all tasks
                        $tasksQuery = "SELECT * FROM `Tasks` 
                        WHERE user_email = '$User' AND status = 'INCOMPLETE';";
                        $allTasks = mysqli_query($conn, $tasksQuery);

            
                        if (mysqli_num_rows($allTasks) > 0){
                            //output data of each row
                            while ($row = mysqli_fetch_array($allTasks)){
                                echo "<a href = '#Task:$row[0]' onclick = 'getTaskDetails($row[0])'> task : [".$row[0]."] -> ".$row[1]."</a>";
                            }
                        }
            
                    }catch(Exception $e){
                        //catch whatever exception is thrown first and display it's message.
                        echo "Error: ".$e->getMessage();
                    }
                ?>
            </div>
            <button class ="createTasksBtn" class="submit-button">Create Task</button>
        </div>
    
        <div id = "ArchTasks" class = "main" style="display: none;">
            <!--This will be the place where Archived task will be displayed.-->
            <h3>Archived Tasks</h3>
            <div class = "tasks">
                <!-- <a href="#Task1">Task 1 - Complete</a>
                <a href="#Task2">Task 2 - Complete</a> -->
                <?php
                    try{
                        $User = 'frederick.umah@makeitall.org.uk';
                        
                        //get all tasks
                        $tasksQuery = "SELECT * FROM `Tasks` 
                        WHERE user_email = '$User' AND status = 'COMPLETE';";
                        $allTasks = mysqli_query($conn, $tasksQuery);
            
                        //$tasksArray = array();
                        //echo "<datalist id = 'possibleCountries'>";
            
                        if (mysqli_num_rows($allTasks) > 0){
                            //output data of each row
                            while ($row = mysqli_fetch_array($allTasks)){
                                echo "<a href = '#Task:$row[0]' onclick = 'getTaskDetails($row[0])'> task : [".$row[0]."] -> ".$row[1]."</a>";
                                //$possibleCountries = $row[0];
                            }
                        }
            
                    }catch(Exception $e){
                        //catch whatever exception is thrown first and display it's message.
                        echo "Error: ".$e->getMessage();
                    }
                ?>
            </div>
        </div>
    
        <div id = "ProjectTasks" class = "main" style="display: none;">
            <!--This will be the page where the project's tasks will be displayed.-->
            <h3>Project  Tasks</h3>
            <div id = "ProjTasks" class = "tasks">
            <!-- This is where the project's task list will be displayed -->
            </div>
        </div>

        <div id = "LeadProjectTasks" class = "main" style="display: none;">
            <!--This will be the page where the project's tasks will be displayed.-->
            <h3>Project  Tasks</h3>
            <div id = "leadProjTasks" class = "tasks">
            <!-- This is where the project's task list will be displayed -->
            </div>
            <button class ="createTasksBtn" class="submit-button">Create Task</button>
        </div>
    

    </div>
    
     <!-- ------------------------------------------all the Modals(pop-ups)--------------------------------------------------------- -->

    <!-- modal for create a Task -->
    <div id = "Overlay" class="overlay hidden"></div> 
    <section id = "createTaskModal" class="modal hidden"> 
        <div class="flex">
            <button class="btn-close" onclick = "closeModal()";>⨉</button>
        </div>
        <h3>Create a Task</h3>
            <form id="createTaskForm">
                <label for="taskTitle">Task Title:</label>
                <input type="text" id="taskTitle" name="taskTitle" class="input-field" required><br><br>

                <label for="taskDescription">Task Description:</label>
                <textarea id="taskDescription" name="taskDescription" class="input-field" row = "4" cols ="50" style = "height: 105px; width: 505px" ></textarea><br><br>

                <label for="taskDeadline">Task Deadline (YYYY-MM-DD):</label>
                <input type="text" id="taskDeadline" name="taskDeadline" class="input-field" required><br><br>

                <label for="assignedTo">Choose User:</label>
                <select id="assignedTo" name="assignedTo" class="input-field" required>
                </select><br><br>

                <input id = "Project_ID" name = "Project_ID" type="hidden"> 

                <button id = "SubmitB" type = "submit" class="submit-button">Create</button>
            </form>
    </section>
    <!-- End of create a Task Modal -->

    <!-- modal for Tasks -->
    <section id = "taskModal" class="modal hidden"> 
        <div class="flex">
            <button class="btn-close" onclick = "closeModal()";>⨉</button>
        </div>
        <input id = "Task_ID" name = "Task_ID" type="hidden">
        <input id = "Permission" name = "Permission" type="hidden"> 
        <div id = "taskInfo"></div>
    </section>
    <!-- End of Task Modal -->
    
    <script> 
        //pass the infomation about the countries into the java script
        let User_ = "<?php echo  $_POST['User']?>";
    </script>
    <script src="ProdFunctions.js"></script>
<body>
</html>