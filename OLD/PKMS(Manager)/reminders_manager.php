<!-- Session Start: Initiates or resumes a session. -->
<?php
session_start();
?>
<!-- HTML Structure: Defines the layout structure using HTML. -->
<div class="black-container">
    <!-- Sets Breadcrumbs -->
    <ul class="breadcrumbs">
        <li class="breadcrumbs-item">
            <a href="/remindersLanding.php" class="breadcrumbs_link">Reminders</a>
        </li>
        <li class="breadcrumbs-item">
            <a href="/PKMS/PKMS(Manager)/ProdRemindersLanding.php" class="breadcrumbs_link--active">My Productivity Reminders</a>
        </li>
    </ul>
    <!-- Manager Email Retrieval: Retrieves the manager's email from the session. -->
    <?php
    $managerEmail = $_SESSION['email']; 

    // Database connection setup
    $username = "team011";
    $password = "JAEWyfUXpzqank7scpWm";
    $servername = "localhost";
    $dbname = "team011";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Query for projects
    $sqlProjects = "SELECT project_id, name, deadline AS project_deadline 
                    FROM Project 
                    WHERE (manager = '$managerEmail' OR team_leader = '$managerEmail') 
                    AND project_status = 'INCOMPLETE'
                    AND deadline <= DATE_ADD(NOW(), INTERVAL 7 DAY)";
    $resultProjects = mysqli_query($conn, $sqlProjects);

    // Initialize HTML content for projects
    $htmlContent = '<div class="posts"><h2>Projects I Lead</h2>';

    // Process each project
    if (mysqli_num_rows($resultProjects) > 0) {
        while ($rowProject = mysqli_fetch_assoc($resultProjects)) {
            // Append project details to HTML content
            $htmlContent .= '<div class="posts"><h3>Project: ' . $rowProject['name'] . ' (Deadline: ' . $rowProject['project_deadline'] . ')</h3>';

            // Query and process tasks for the current project
            $sqlTasks = "SELECT title, deadline FROM Tasks WHERE project_id = '" . $rowProject['project_id'] . "' AND status = 'INCOMPLETE' ORDER BY deadline";
            $resultTasks = mysqli_query($conn, $sqlTasks);

            if (mysqli_num_rows($resultTasks) > 0) {
                while ($rowTask = mysqli_fetch_assoc($resultTasks)) {
                    // Append task details to HTML content
                    $deadlineClass = (strtotime($rowTask['deadline']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24) <= 3 ? 'high-priority' : 'regular-priority';
                    $htmlContent .= '<div class="task ' . $deadlineClass . '" title="' . $deadlineClass . '">' . $rowTask['title'] . ' (Deadline: ' . $rowTask['deadline'] . ')</div>';
                }
            } else {
                $htmlContent .= '<p>No incomplete tasks found for this project.</p>';
            }

            $htmlContent .= '</div>'; // Close project posts div
        }
    } else {
        $htmlContent .= '<div class="posts">';
        $htmlContent .= '<p>No projects due within the next week.</p>';
        $htmlContent .= '</div>'; // Close projects container div

    }
    $htmlContent .= '</div>'; // Close projects container div

    echo $htmlContent; // Output projects content

    // Query for personal tasks
    $sqlPersonalTasks = "SELECT title, deadline FROM Tasks WHERE project_id = 0 AND user_email = '$managerEmail' AND status = 'INCOMPLETE' AND deadline <= DATE_ADD(NOW(), INTERVAL 7 DAY) ORDER BY deadline";
    $resultPersonalTasks = mysqli_query($conn, $sqlPersonalTasks);

    // Initialize HTML content for personal tasks
    $htmlContentPersonalTasks = '<div class="posts"><h2>My Personal Tasks</h2>';

    // Process each personal task
    if (mysqli_num_rows($resultPersonalTasks) > 0) {
        $htmlContentPersonalTasks .= '<div class="posts">';

        while ($rowPersonalTask = mysqli_fetch_assoc($resultPersonalTasks)) {
            // Append task details to HTML content
            $deadlineClass = (strtotime($rowPersonalTask['deadline']) - strtotime(date('Y-m-d'))) / (60 * 60 * 24) <= 3 ? 'high-priority' : 'regular-priority';

            $htmlContentPersonalTasks .= '<div class="task ' . $deadlineClass . '" title="' . $deadlineClass . '">' . $rowPersonalTask['title'] . ' (Deadline: ' . $rowPersonalTask['deadline'] . ')</div>';
        }
        $htmlContentPersonalTasks .= '</div>';


    } else {
        $htmlContentPersonalTasks .= '<div class="posts">';

        $htmlContentPersonalTasks .= '<p>No personal tasks due within the next week.</p>';
        $htmlContentPersonalTasks .= '</div>';

    }
    $htmlContentPersonalTasks .= '</div>'; // Close personal tasks container div

    echo $htmlContentPersonalTasks; // Output personal tasks content

    mysqli_close($conn); // Close the database connection
    ?>
</div>
<!-- CSS Styles: Defines CSS styles for the HTML elements to control their appearance. -->
<style>
.remindersGrid-container {
    display: grid;
    gap: 20px;
    padding: 20px;
}

.posts,.message-container {
    width: 100%;
    border: 1px solid #000;
    background-color: #f0f0f0;
    padding: 20px;
    border-radius: 10px;
    box-sizing: border-box;
    margin-bottom: 20px;
}

.task {
    padding: 10px;
    border-radius: 5px;
    margin-bottom: 10px;
    position: relative; /* Add position relative */
}

.high-priority {
    color: red;
    background-color: #ffe6e6; /* Light red background */
}

.regular-priority {
    color: darkorange;
    background-color: #ffe0b3; /* Light orange background */
}

.task:hover:after {
    content: attr(title);
    position: absolute;
    background: rgba(0, 0, 0, 0.8);
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    white-space: nowrap;
}
.black-container {
    background-color: black;
    padding: 20px; /* Adjust padding as needed */
    border-radius: 10px;

}
.breadcrumbs {
padding: 10px;
margin-right: 79vh;
}

.breadcrumbs-item{
display: inline-block;
}

.breadcrumbs-item:not(:last-of-type)::after{
    content: '/';
    margin: 0.5px;
    color: #cccccc;
}

.breadcrumbs_link{
text-decoration: none;
color: #999999
}

.breadcrumbs_link:hover{
    text-decoration: underline;
}

.breadcrumbs_link--active{
color:#ed992b;
font-weight: 500;

}
</style>
