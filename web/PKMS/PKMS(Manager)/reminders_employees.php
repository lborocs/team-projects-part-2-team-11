<?php
session_start();
?>
<div class="black-container">
<!-- BreadCrumbs -->
<ul class="breadcrumbs">
    <li class="breadcrumbs-item">
        <a href="/remindersLanding.php" class="breadcrumbs_link">Reminders</a>
    </li>
    <li class="breadcrumbs-item">
        <a href="/PKMS/PKMS(Manager)/ProdRemindersLanding.php"  class="breadcrumbs_link--active"
            breadcrumbs_link--active>My Productivity Reminders</a>
    </li>
</ul>

<?php
$employeeEmail = $_SESSION['email']; // Example employee email

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

// Check if the employee is a team leader for any project
$sqlTeamLeaderProjects = "SELECT p.project_id, p.name AS project_name, p.deadline AS project_deadline 
                          FROM Project p
                          WHERE p.team_leader = '$employeeEmail' AND p.deadline <= DATE_ADD(NOW(), INTERVAL 7 DAY) AND p.project_status = 'INCOMPLETE'
                          ORDER BY p.deadline";

$resultTeamLeaderProjects = mysqli_query($conn, $sqlTeamLeaderProjects);

// Initialize an empty variable to store the HTML content for team leaders
$htmlContentTeamLeader = '';
$htmlContentTeamLeader .= '<div class="posts">';
$htmlContentTeamLeader .= '<h2>Projects I Lead</h2>';

// Check if there are any projects where the employee is a team leader
if (mysqli_num_rows($resultTeamLeaderProjects) > 0) {
    // Loop through each project where the employee is a team leader
    while ($rowTeamLeaderProject = mysqli_fetch_assoc($resultTeamLeaderProjects)) {
        $projectId = $rowTeamLeaderProject['project_id'];
        $projectName = $rowTeamLeaderProject['project_name'];
        $projectDeadline = $rowTeamLeaderProject['project_deadline'];
        
        // Start the HTML content for the project
        $htmlContentTeamLeader .= '<div class="posts">';
        $htmlContentTeamLeader .= '<h3>Project: ' . $projectName . ' (Deadline: ' . $projectDeadline . ')</h3>';
        
        // Query to fetch incomplete tasks for this project assigned to the employee
        $sqlTasks = "SELECT title, deadline, task_id 
                     FROM Tasks 
                     WHERE project_id = '$projectId' AND status = 'INCOMPLETE'
                     AND deadline <= DATE_ADD(NOW(), INTERVAL 7 DAY)
                     ORDER BY deadline";
                     
        $resultTasks = mysqli_query($conn, $sqlTasks);

        // Check if there are any incomplete tasks for this project assigned to the employee
        if (mysqli_num_rows($resultTasks) > 0) {
            // Loop through each incomplete task
            while ($rowTask = mysqli_fetch_assoc($resultTasks)) {
                $taskId = $rowTask['task_id'];
                $taskTitle = $rowTask['title'];
                $taskDeadline = $rowTask['deadline'];

                // Calculate the difference in days between today and the task deadline
                $today = strtotime(date('Y-m-d'));
                $deadline = strtotime($taskDeadline);
                $difference = ($deadline - $today) / (60 * 60 * 24); // Convert seconds to days

                // Determine the CSS class and tooltip message based on the deadline
                if ($difference <= 3) {
                    $deadlineClass = 'high-priority';
                    $message = 'High Priority';
                } else {
                    $deadlineClass = 'regular-priority';
                    $message = 'Normal Priority';
                }

                // Add a container for each task with appropriate styles and tooltip message
                $htmlContentTeamLeader .= '<div class="task ' . $deadlineClass . '" title="' . $message . '">' . $taskTitle . ' (Deadline: ' . $taskDeadline . ')</div>';
            }
        } else {
            // No incomplete tasks found for this project assigned to the employee
            $htmlContentTeamLeader .= '<p>No incomplete tasks found for this project.</p>';
        }

        $htmlContentTeamLeader .= '</div>'; // Close the .posts class
    }
} else {
    // No projects due for the team leader
    $htmlContentTeamLeader .= '<div class="posts">';
    $htmlContentTeamLeader .= '<p>No projects due for the next week.</p>';
    $htmlContentTeamLeader .= '</div>'; // Close the .posts class
}


// Query to fetch projects where the employee is a regular team member
$sqlRegularEmployeeProjects = "SELECT p.project_id, p.name AS project_name, p.deadline AS project_deadline
                                FROM Project p
                                JOIN Tasks t ON p.project_id = t.project_id
                                WHERE (t.user_email = '$employeeEmail' OR p.team_leader = '$employeeEmail') AND t.deadline <= DATE_ADD(NOW(), INTERVAL 7 DAY)
                                AND p.project_status='INCOMPLETE'
                                GROUP BY p.project_id
                                ORDER BY t.deadline";

$resultRegularEmployeeProjects = mysqli_query($conn, $sqlRegularEmployeeProjects);

// Initialize an empty variable to store the HTML content for regular team members
// $htmlContentRegularEmployee = '';
$htmlContentRegularEmployee .= '<div class="posts">';
$htmlContentRegularEmployee .= '<h2>Projects I am working on as an Employee</h2>';

// Check if there are any projects where the employee is a regular team member
if (mysqli_num_rows($resultRegularEmployeeProjects) > 0) {
    // Loop through each project where the employee is a regular team member
    while ($rowRegularEmployeeProject = mysqli_fetch_assoc($resultRegularEmployeeProjects)) {
        $projectId = $rowRegularEmployeeProject['project_id'];
        $projectName = $rowRegularEmployeeProject['project_name'];
        $projectDeadline = $rowRegularEmployeeProject['project_deadline'];
        
        // Start the HTML content for the project
        $htmlContentRegularEmployee .= '<div class="posts">';
        $htmlContentRegularEmployee .= '<h3>Project: ' . $projectName . ' (Deadline: ' . $projectDeadline . ')</h3>';
        
        // Query to fetch incomplete tasks for this project assigned to the employee
        $sqlTasks = "SELECT title, deadline, task_id 
                     FROM Tasks 
                     WHERE project_id = '$projectId' AND user_email = '$employeeEmail' AND status = 'INCOMPLETE'
                     AND deadline <= DATE_ADD(NOW(), INTERVAL 7 DAY)
                     ORDER BY deadline";
                     
        $resultTasks = mysqli_query($conn, $sqlTasks);

        // Check if there are any incomplete tasks for this project assigned to the employee
        if (mysqli_num_rows($resultTasks) > 0) {
            // Loop through each incomplete task
            while ($rowTask = mysqli_fetch_assoc($resultTasks)) {
                $taskId = $rowTask['task_id'];
                $taskTitle = $rowTask['title'];
                $taskDeadline = $rowTask['deadline'];

                // Calculate the difference in days between today and the task deadline
                $today = strtotime(date('Y-m-d'));
                $deadline = strtotime($taskDeadline);
                $difference = ($deadline - $today) / (60 * 60 * 24); // Convert seconds to days

                // Determine the CSS class and tooltip message based on the deadline
                if ($difference <= 3) {
                    $deadlineClass = 'high-priority';
                    $message = 'High Priority';
                } else {
                    $deadlineClass = 'regular-priority';
                    $message = 'Normal Priority';
                }

                // Add a container for each task with appropriate styles and tooltip message
                $htmlContentRegularEmployee .= '<div class="task ' . $deadlineClass . '" title="' . $message . '">' . $taskTitle . ' (Deadline: ' . $taskDeadline . ')</div>';
            }
        } else {
            $htmlContentRegularEmployee .= '<p>No tasks due in the next week for this project.</p>';
            
        }

        $htmlContentRegularEmployee .= '</div>'; // Close the .posts class
    }
}else {
    // No projects found where the user is a regular employee
    // No personal tasks found for the employee
    $htmlContentRegularEmployee .= '<div class="posts">';
    $htmlContentRegularEmployee.= '<p>No tasks due for the next week.</p>';
    // $htmlContentRegularEmployee .= '<div class="message-container"><p>No tasks found for the next week.</p></div>';
    $htmlContentRegularEmployee .= '</div>'; // Close the .posts class
    // $htmlContentRegularEmployee .= '<p>No projects found where you are working as an employee.</p>';
}

$htmlContentRegularEmployee .= '</div>'; // Close the regular employee section

// Output the HTML content for team leaders and regular employees
echo '<div class="team-leader-container">' . $htmlContentTeamLeader . '</div>';
echo '<div class="regular-employee-container">' . $htmlContentRegularEmployee . '</div>';



// Query to fetch personal tasks for the employee
$sqlPersonalTasks = "SELECT title, deadline, task_id 
                     FROM Tasks 
                     WHERE project_id = 0 AND user_email = '$employeeEmail' AND status = 'INCOMPLETE'
                     AND deadline <= DATE_ADD(NOW(), INTERVAL 7 DAY)
                     ORDER BY deadline";
                     
$resultPersonalTasks = mysqli_query($conn, $sqlPersonalTasks);

// Initialize an empty variable to store the HTML content for personal tasks
$htmlContentPersonalTasks = '';
$htmlContentPersonalTasks .= '<div class="posts">';
$htmlContentPersonalTasks .= '<h2>My Personal Tasks</h2>';

// Check if there are any personal tasks for the employee
if (mysqli_num_rows($resultPersonalTasks) > 0) {
    // Start the HTML content for personal tasks
    $htmlContentPersonalTasks .= '<div class="posts">';
    // $htmlContentPersonalTasks .= '<h3>My Tasks</h3>';
    
    // Loop through each personal task
    while ($rowPersonalTask = mysqli_fetch_assoc($resultPersonalTasks)) {
        $taskTitle = $rowPersonalTask['title'];
        $taskDeadline = $rowPersonalTask['deadline'];

        // Calculate the difference in days between today and the task deadline
        $today = strtotime(date('Y-m-d'));
        $deadline = strtotime($taskDeadline);
        $difference = ($deadline - $today) / (60 * 60 * 24); // Convert seconds to days

        // Determine the CSS class and tooltip message based on the deadline
        if ($difference <= 3) {
            $deadlineClass = 'high-priority';
            $message = 'High Priority';
        } else {
            $deadlineClass = 'regular-priority';
            $message = 'Normal Priority';
        }

        // Add a container for each personal task with appropriate styles and tooltip message
        $htmlContentPersonalTasks .= '<div class="task ' . $deadlineClass . '" title="' . $message . '">' . $taskTitle . ' (Deadline: ' . $taskDeadline . ')</div>';
    }

    $htmlContentPersonalTasks .= '</div>'; // Close the .posts class
} else {
       // No personal tasks found for the employee
       $htmlContentPersonalTasks .= '<div class="posts">';
       $htmlContentPersonalTasks .= '<p>No tasks found for the next week.</p>';
       $htmlContentPersonalTasks .= '</div>'; // Close the .posts class
}

// Output the HTML content for personal tasks
echo '<div class="personal-tasks-container">' . $htmlContentPersonalTasks . '</div>';

// Close the database connection
mysqli_close($conn);
?>
</div>


<style>
.team-leader-container, .regular-employee-container, .personal-tasks-container, ..message-container {
    display: grid;
    gap: 20px;
    padding: 20px;
}

.posts {
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
.message-container {
    background-color: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
}

</style>