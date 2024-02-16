<?php
// set up the connection to the database
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

// Query to fetch incomplete projects (My Projects)
$sqlMyProjects = "SELECT name 
                    FROM `Project` 
                    WHERE (manager = 'miles.morales@makeitall.org.uk') 
                    AND project_status='INCOMPLETE'";
$resultMyProjects = $conn->query($sqlMyProjects);

// Array to store incomplete project names
$myProjectNames = array();

if ($resultMyProjects->num_rows > 0) {
    while ($row = $resultMyProjects->fetch_assoc()) {
        $myProjectNames[] = $row['name'];
    }
}

// Query to fetch complete projects (Archived Projects)
$sqlArchivedProjects = "SELECT name 
                        FROM `Project` 
                        WHERE (manager = 'miles.morales@makeitall.org.uk') 
                        AND project_status='COMPLETE'";
$resultArchivedProjects = $conn->query($sqlArchivedProjects);

// Array to store complete project names
$archivedProjectNames = array();

if ($resultArchivedProjects->num_rows > 0) {
    while ($row = $resultArchivedProjects->fetch_assoc()) {
        $archivedProjectNames[] = $row['name'];
    }
}
// Close connection
mysqli_close($conn);
?>

<!-- Container for My Projects -->
<!-- <div>
    <a id="CreateProject" href="#createProject">Create A Project</a>
</div> -->
<div id="myProjects">
<div id="myProject1">

    <h3>My Projects</h3>
    <?php
    // Display incomplete projects
    if (!empty($myProjectNames)) {
        foreach ($myProjectNames as $projectName) {
            echo '<a class="project-link" href="#" data-project="' . $projectName . '">' . $projectName . '</a>';
        }
    } else {
        echo '<p>No incomplete projects found.</p>';
    }
    ?>
</div>

<!-- Container for Archived Projects -->
<div id="archivedProjects">
    <h3>Archived Projects</h3>
    <?php
    // Display archived projects
    if (!empty($archivedProjectNames)) {
        foreach ($archivedProjectNames as $projectName) {
            echo '<a class="project-link" href="#" data-project="' . $projectName . '">' . $projectName . '</a>';
        }
    } else {
        echo '<p>No archived projects found.</p>';
    }
    
    ?>
</div>
</div>
