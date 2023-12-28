<!DOCTYPE html>
<html style="background-color:FFFFFF">
<head>
    <link rel="stylesheet" href="styles2.css">
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

        $User = 'frederickumah@makeitall.org.uk'
        
        //get all tasks
        $tasksQuery = 'SELECT * 
        FROM `Tasks` 
        WHERE user_email = "frederickumah@makeitall.org.uk"'

        allTasks = mysqli_query($conn, $tasksQuery)

        $tasksArray = array();
        //echo "<datalist id = 'possibleCountries'>";
        if (mysqli_num_rows($allTasks) > 0){
            //output data of each row
            while ($row = mysqli_fetch_array($allTasks)){
            echo $row;
            //$possibleCountries = $row[0];
            }
        }
        //echo "</datalist>";

        // INSERT INTO `Tasks` (`task_id`, `Title`, `Description`, `Deadline`, `user_email`) 
        // VALUES ('103', 'Testing Task', '', '2023-12-31', 'frederickumah@makeitall.org.uk');

    ?>

    <!-- Just a little something -->
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
            <div style="height: 68%;">
            <h3>My Projects</h3>
            <a id="Project1B" href="#Proj1">Project 1</a>
            <a id="Project2B" href="#Proj2">Project 2</a>
            </div>
        </div>

       <!-- ---------------------------------------------all the possible contents in the main------------------------------------------------------ -->

        <div id = "CurTasks", class = "main">
            <!--This will be the place where current task will be displayed.-->
            <h3>Current Tasks</h3>
            <div>
                <a href="#Task1">Task 1</a>
                <a href="#Task2">Task 2</a>
            </div>
        </div>
    
        <div id = "ArchTasks" class = "main" style="display: none;">
            <!--This will be the place where Archived task will be displayed.-->
            <h3>Archived Tasks</h3>
            <div class = "tasks">
                <a href="#Task1">Task 1 - Complete</a>
                <a href="#Task2">Task 2 - Complete</a>
            </div>
        </div>
    
        <div id = "Project1Tasks" class = "main" style="display: none;">
            <!--This will be the page where the project1 tasks will be displayed.-->
            <h3>Project 1 Tasks</h3>
    
            <div class = "tasks_Table">
                <table>
                    <tr><th>Unassigned</th> <th>Assigned</th> <th>In-Progress</th> <th>Team members</th></tr>
                    <tr><td><a href="#Task2">Task 2</a></td><td><a href="#Task1">Task 1</a></td><td></td><td><a href="#Task4">Task 4</a></td></tr>
                    <tr><td><a href="#Task3">Task 3</a></td><td></td><td></td><td><a href="#Task5">Task 5</a></td></tr>
                    <tr><td><a href="#Task6">Task 6</a></td><td></td><td></td><td></td></tr>
                </table>
            </div>
    
            <button id="createTasksBtn">Create Task</button>
        </div>
    
        <div id = "Project2Tasks" class = "main" style="display: none;">
            <!--This will be the main part of the page where things will be displyed, such as tasks, and all the buttons and etc.-->
            <h3>Project 2 Tasks</h3>
            <div class = "tasks">
                <a href="#Task1">Task 1</a>
                <a href="#Task2">Task 2</a>
            </div>
        </div>
  
      

    </div>
    
     <!-- ------------------------------------------all the Modals(pop-ups)--------------------------------------------------------- -->

     <!-- <div id="CreateTaskModal" class="modal">
        
        <div class="form-container">
          
          <form class="form">
            <span class="close">&times;</span> 
              <h1>Create a Task</h1>
              <label for="taskName">Task Name:</label>
              <input type="text" id="taskName" name="taskName" required><br><br>
  
              <label for="taskDescription">Task Description:</label>
              <input type="text" id="taskDescription" name="taskDescription"><br><br>
  
              <br>
              <label for="taskAssignee">Choose Team Member:</label>
              <select id="taskAssignee" name="taskAssignee">
                  <option value="Member 1">Member 1</option>
                  <option value="Member 2">Member 2</option>
                  <option value="Member 3">Member 3</option>
              </select><br><br>
              <div class="button-container">
                  <input type="submit" value="Create Task">
              </div>
          </form>
        </div>
      </div>
  
      <div id="TaskDetails" class="modal">
       
        <div class="modal-content">
          <span class="close">&times;</span> 
          <h1 id = "TaskTitle"></h1>
  
          <p>Team Leader : *Username*</p>
          <p>Date Assigned : *Date*</p>
  
          <div style=" padding: 20px; border: #111; border-width: 1px; border-radius: 10px;">
            <p>Description</p>
          </div>
  
          <div style="display: flex">
            <label for="Status">Status:</label>
            <select id="Status" name="Status">
                <option value="In-Progress">In-Progress</option>
                <option value="Complete">Complete</option>
            </select>
  
            <label for="taskAssignee">Choose Team Member:</label>
              <select id="taskAssignee" name="taskAssignee">
                  <option value="Member 1">Member 1</option>
                  <option value="Member 2">Member 2</option>
                  <option value="Member 3">Member 3</option>
              </select><br><br>
          </div>
  
        </div>
      </div>  -->

    <!-- <script src="scriptPKMS.js"></script> -->
<body>
</html>