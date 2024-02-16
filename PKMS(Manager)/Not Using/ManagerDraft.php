<!DOCTYPE html>
<html style="background-color:FFFFFF">
<head>
    <link rel="stylesheet" href="ManagerDraft.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> PKMS Manager Draft</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div id="ProjectList">
        <div class="SideNav">
            <!-- my projects section 1 -->
            <div>
                <a id="CreateProject" href="#createProject">Create A Project</a>
            </div>
            <div id="myProjects">
                <h3>My Projects</h3>
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

                    $sql = "SELECT name 
                            FROM `Project` 
                            WHERE (manager = 'miles.morales@makeitall.org.uk' OR team_leader = '$loggedInManagerEmail') 
                            AND project_status='INCOMPLETE'";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        // Output each project as a link
                        while ($row = $result->fetch_assoc()) {
                            echo '<a href="#' . $row['name'] . '">' . $row['name'] . '</a>';
                        }
                    } else {
                        echo '<h3>No Projects Found</h3>';
                    }

                    // Close connection
                    mysqli_close($conn);
                ?>
            </div>
            <!-- my arhcived section 2 -->
            <div style="height: 70%;">
                <h3>Archived Projects</h3>
                <a id="Project1B" href="#Proj1">Project 106</a>
                <a id="Project2B" href="#Proj2">Project 209</a>
            </div>
        </div>
        <!-- --------------------------------------------------------- -->
        <div id = "ProjectDetails" class = "main">
            <!--This will be the place where the charts will be shown.-->
            <h3> Project 101 </h3>
            <!--Deadline-->
            <div class="graph-container">
                <div class="graph graph-2">
                    <h5 class="icon-container">
                    <img src= "images/deadline.png" alt="deadline icon">
                    Project Deadline: 01-01-2025
                </h5>
                </div>
            </div>
            <!--Team details and tasks-->
            <div class="graph-container">
                <div class="graph graph-1 team-tasks">
                    <table class="team-table">
                        <thead>
                            <tr>
                                <th>Team Member</th>
                                <th>Email</th>
                                <th>Tasks Assigned</th>
                                <th>Task Details</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Team Leader</td>
                                <td>leader@example.com</td>
                                <td>8</td>
                                <td>
                                    Task 1: Complete<br>
                                    Task 2: Incomplete
                                </td>
                            </tr>
                            <tr>
                                <td>Team Member 1</td>
                                <td>member1@example.com</td>
                                <td>5</td>
                                <td>
                                    Task 1: Complete<br>
                                    Task 2: Incomplete
                                </td>
                            </tr>
                            <!-- Add rows for other team members -->
                        </tbody>
                    </table>
                </div>
                </div>
                <!--Completion chart-->
                <div class="graph-container" style="height: 400px; overflow: auto;">
                    <div class="graph graph-3">
                        <canvas id="completionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <script>
        // Sample data (replace with your actual data)
        const employeeData = {
            employee1: [1, 0, 0, 0, 0, 0],
            employee2: [0, 1, 0, 1, 0, 1],
            employee3: [1, 0, 1, 0, 1, 0],
        };

        // Get the canvas element
        const ctx = document.getElementById('completionChart').getContext('2d');

        // Create the chart
        const completionChart = new Chart(ctx, {
            type: 'bar', // Use 'bar' chart type
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                datasets: Object.keys(employeeData).map((employee, index) => ({
                    label: employee,
                    data: employeeData[employee],
                    backgroundColor: index === 0 ? 'lightgreen' : (index === 1 ? 'pink' : 'silver'), // Assigning colors
                    borderColor: 'black',
                    borderWidth: 2,
                })),
            },
            options: {
                indexAxis: 'x', // Display bars horizontally
                scales: {
                    y: {
                        ticks: { 
                            stepSize: 1,
                            callback: (value) => (value === 0 || value === 1 ? value : ''),
                        },
                    },
                },
                layout: {
                    padding: {
                    left: 20, // Adjust the left padding to create space between legend and graph
                    right: 20,
                    top: 20,
                    bottom: 0,
                },
            },
        },
        });
        // Function to append dynamic project links to the "My Projects" section
        // Function to append dynamic project links to the "My Projects" section
        function appendProjects(projectNames) {
            const myProjects = document.getElementById('myProjects');
            const projectsHTML = projectNames.map(name => `<a href="#${name}">${name}</a>`).join('');
            myProjects.innerHTML = `<h3>My Projects</h3>${projectsHTML}`;
        }

        // Make an AJAX request to fetch project names from the PHP script
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const projectNames = JSON.parse(this.responseText);
                appendProjects(projectNames);
            }
        };
        xhttp.open("GET", "getProjects.php", true);
        xhttp.send();
    </script>
</body>
</html>

