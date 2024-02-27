

<!DOCTYPE html>
<html style="background-color:FFFFFF">
<head>
    <link rel="stylesheet" href="ManagerDraft.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> PKMS Manager Draft</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>
<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
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
    ?>
    <?php

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    // Database connection details
    $servername = "localhost";
    $username = "team011";
    $password = "JAEWyfUXpzqank7scpWm";
    $dbname = "team011";

    // Create connection
    $conn = mysqli_connect($servername, $username, $password, $dbname);

    // Check connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    } else {
        // Log a message indicating successful connection
        error_log("Connected to the database successfully!");
    }

    // Fetch team members from the database
    $teamMembersQuery = "SELECT username, role FROM Users_Details WHERE username IS NOT NULL AND role='Employee'";
    $teamMembersResult = mysqli_query($conn, $teamMembersQuery);

    // Fetch team members from the database for createTaskForm
    $teamMembersQueryForTask = "SELECT username, role FROM Users_Details WHERE username IS NOT NULL AND role='Employee'";
    $teamMembersResultForTask = mysqli_query($conn, $teamMembersQueryForTask);

     // Fetch team members from the database for editProjectForm
     $teamMembersQueryForEdit = "SELECT username, role FROM Users_Details WHERE username IS NOT NULL AND role='Employee'";
     $teamMembersResultForEdit = mysqli_query($conn, $teamMembersQueryForEdit);
    ?>
    <!-- modal for create a project -->
    <div class="overlay hidden"></div> 
    <section class="modal hidden"> 
        <div class="flex">
            <button class="btn-close">⨉</button>
        </div>
        <h3>Create a Project</h3>
            <form id="createProjectForm">
                <label for="projectName">Project Name:</label>
                <input type="text" id="projectName" name="projectName" class="input-field" required><br><br>
                <label for="projectDeadline">Project Deadline (YYYY-MM-DD):</label>
                <input type="text" id="projectDeadline" name="projectDeadline" class="input-field" required><br><br>
                <label for="teamLeader">Choose Team Leader:</label>
                <select id="teamLeader" name="teamLeader" class="input-field" required>
                    <option value="">Select Team Leader</option>
                    <?php
                    if (mysqli_num_rows($teamMembersResult) > 0) {
                        while ($row = mysqli_fetch_assoc($teamMembersResult)) {
                            echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No team members available</option>';
                    }
                    ?>
                </select><br><br>
                <button type="submit" class="submit-button">Create</button>
            </form>
    </section>
    <!-- End of create a project Modal -->
    <!-- Edit Project Modal -->
    <div class="editProject-overlay hidden"></div> 
    <section class="editProject-modal hidden"> 
        <div class="flex">
            <button class="editProject-btn-close">⨉</button>
        </div>
        <h3>Edit Project</h3>
            <form id="editProjectForm">
                <label for="projectName">Project Name:</label>
                <input type="text" id="projectName1" name="projectName" class="input-field" required><br><br>
                <label for="editDeadline">Project Deadline (YYYY-MM-DD):</label>
                <input type="text" id="editDeadline1" name="editDeadline" class="input-field" required><br><br>
                <label for="editDuration">Edit/ Set Project Duration:</label>
                <input type="text" id="editDuration1" name="editDuration" class="input-field"><br><br>
                <label for="teamLeader">Choose Team Leader:</label>
                <select id="teamLeader1" name="teamLeader" class="input-field">
                    <option value="">Select Team Leader</option>
                    <?php
                    mysqli_data_seek($teamMembersResultForTask, 0);

                    if (mysqli_num_rows($teamMembersResultForEdit) > 0) {
                        while ($row = mysqli_fetch_assoc($teamMembersResultForEdit)) {
                            echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No team members available</option>';
                    }
                    ?>
                </select><br><br>
                <button type="submit" class="submit-button">Create</button>
            </form>
    </section>
    <!-- End of Edit Project Modal -->
    <!-- Create a task modal -->
    <div class="task-overlay hidden"></div> 
    <section class="task-modal hidden"> 
        <div class="flex">
            <button class="task-btn-close">⨉</button>
        </div>
        <h3>Create a Task</h3>
            <form id="createTaskForm">
                <label for="taskName">Task Title:</label>
                <input type="text" id="taskName" name="taskName" class="input-field" required><br><br>
                <!-- <label for="taskID">Task ID:</label>
                <input type="text" id="taskID" name="taskID" class="input-field" required><br><br> -->
                <label for="taskDescription">Task Description:</label>
                <input type="text" id="taskDescription" name="taskDescription" class="input-field"><br><br>
                <label for="taskDeadline">Task Deadline (YYYY-MM-DD):</label>
                <input type="text" id="taskDeadline" name="taskDeadline" class="input-field" required><br><br>
                <label for="taskDeadline">Task Estimated Time:</label>
                <input type="text" id="taskEstimatedTime" name="taskEstimatedTime" class="input-field" ><br><br>
                <label for="teamMember">Choose User:</label>
                <select id="teamMember" name="teamMember" class="input-field">
                    <option value="">Select User</option>
                    <?php
                    mysqli_data_seek($teamMembersResultForTask, 0);

                    //echo '<option value="">Select User</option>';
                    if (mysqli_num_rows($teamMembersResultForTask) > 0) {
                        while ($row = mysqli_fetch_assoc($teamMembersResultForTask)) {
                            echo '<option value="' . $row['username'] . '">' . $row['username'] . '</option>';
                        }
                    } else {
                        echo '<option value="">No team members available</option>';
                    }
                    ?>
                </select><br><br>
                <button type="submit" class="submit-button">Create</button>
            </form>
    </section>
    <!-- End of create a task modal -->
    <!-- Delete project modal -->
    <div class="delete-overlay hidden"></div>
    <section class="delete-modal hidden">
        <div class="flex">
            <button class="delete-btn-close">⨉</button>
        </div>
        <h3>Delete Project</h3>
        <p>Are you sure you want to delete the project? All content and data will be lost once deleted.</p>
        <button id="confirmDeleteBtn" class="submit-button">Confirm Delete</button>
    </section>
    <!-- End of delete project modal -->


    <div id="ProjectList">
    <!-- Breadcrumbs -->
    <ul class="breadcrumbs">
             <li class="breadcrumbs-item">
                <a href="/PKMS/PKMS(Manager)/ManagerLanding.php" class="breadcrumbs_link">Manage</a>
            </li>
            <li class="breadcrumbs-item">
                <a href="/PKMS/PKMS(Manager)/ManagerDashboard.php"  class="breadcrumbs_link--active"
                    breadcrumbs_link--active>My Dashboard</a>
                </li>
            </ul>
        <!-- This holds all Project details -->
        <div id = "SideTemp" class="SideNav">
            <div>
                <a id="CreateProject" href="#createProject">Create A Project</a>
            </div>
            <?php
            // Includes the list of projects from an external php file that is created dynamically
            include 'projectsList.php'; ?>
        </div>
        <!-- --------------------------------------------------------- -->
        <div id = "ProjectDetails" class = "main">
            <!--This will be the place where the charts will be shown.-->
            <div class="graph-container">
                <!-- Displays all buttons that are used to interact with a single project that is selected -->
                <div class="graph graph-4">
                    <a id="CreateTask" href="#createTask">Create A Task</a>
                    <a id="EditProject" href="#editProject">Edit Project</a>
                    <a id="DeleteProject" href="#deleteProject">Delete Project</a>
                    <a id="CompleteProject" href="#completeProject">Set Complete/ Archive Project</a>
                </div>
                <!-- This displays the deadline -->
                <div class="graph graph-2">
                    <h5 class="icon-container">&nbsp;&nbsp;
                    <img src= "images/deadline.png" alt="deadline icon">
                    &nbsp;&nbsp;Deadline:&nbsp;&nbsp;&nbsp;&nbsp;<span id="deadline"></span>
                </h5>
                </div>
            </div>
            <div class="graph-container">
                <div class="graph graph-1">
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="difference"></span>
                </div>
            </div>


            <!--Team details and tasks table-->
            <div class="graph-container">
                <div class="graph graph-1 team-tasks">
                    <table class="team-table">
                        <thead>
                            <tr>
                                <th><h5>Team Member</h5></th>
                                <th><h5>No: Of Tasks Assigned</h5></th>
                                <th><h5>Task Details</h5></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                </div>
                <!--Completion chart/ Donut chart-->
                <div class="graph-container" style="width:auto;height: auto; overflow: auto;">
                    <div class="graph graph-3" id="donutChartConatiner">
                        <h3>Project Completion</h3>
                        <canvas id="donutChart"></canvas>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
        <script src="script.js"></script>
</body>
</html>

