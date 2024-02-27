// Function to trigger click on the first project link
function triggerFirstProjectLinkClick() {
    // Get the first project link
    const firstProjectLink = document.querySelector('.project-link');
        // If a project link exists, trigger a click on it
    if (firstProjectLink) {
        firstProjectLink.click();
    }
}
function fetchProjectDeadline(projectName) {
    // Make an AJAX request to fetch project deadline
    fetch('fetch_project_deadline.php?projectName=' + encodeURIComponent(projectName))
        .then(response => response.text())
        .then(deadline => {
            // Update the deadline span with the fetched deadline
            document.getElementById('deadline').textContent = deadline;
        })
        .catch(error => {
            // Log and handle errors in fetching the deadline
            console.error('Error fetching project deadline:', error);
            document.getElementById('deadline').textContent = 'Error fetching deadline';
        });
}

function fetchHoursDifference(projectName) {
    // Make an AJAX request to fetch the difference in hours
    fetch('fetch_hours_difference.php?projectName=' + encodeURIComponent(projectName))
        .then(response => response.text())
        .then(difference => {
            // Update the difference span with the fetched difference in hours
            document.getElementById('difference').textContent = difference;
        })
        .catch(error => {
            // Log and handle errors in fetching the difference in hours
            console.error('Error fetching difference in hours:', error);
            document.getElementById('difference').textContent = 'Error fetching difference';
        });
}


function fetchProjectID(projectName) {
    // Make an AJAX request to get the project ID based on the project name
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `getProjectID.php?projectName=${encodeURIComponent(projectName)}`, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                // Retrieve the project ID from the response
                const projectId = xhr.responseText;
                // Include the project ID in the form data or perform other actions
            } else {
                console.error("Error:", xhr.statusText);
            }
        }
    };
    xhr.send();
}
// Event listener to trigger actions when the DOM content is loaded
document.addEventListener('DOMContentLoaded', function() {
    reload();
    // Trigger click on the first project link
    triggerFirstProjectLinkClick();
});


function reload(){
    // Add event listeners to project links for both tasks and project deadlines
    const projectLinks = document.querySelectorAll('.project-link');
    projectLinks.forEach(function(projectLink) {
        projectLink.addEventListener('click', function(event) {
            event.preventDefault(); // Prevent default link behavior
            // Remove active class from all project links
            projectLinks.forEach(link => {
                link.classList.remove('active');
            });
            // Add active class to the clicked project link
            this.classList.add('active');
            const projectName = this.dataset.project; // Get the project name from data attribute
            const projectId = this.dataset.projectId; // Get the project ID from data attribute
            // Update the project name in the HTML
            const projectHeader = document.querySelector('#ProjectDetails h3');
            projectHeader.textContent = projectName;
            fetchHoursDifference(projectName);
            fetchTaskDetails(projectName);
            fetchProjectDeadline(projectName);
            fetchProjectID(projectName); // Fetch project ID based on the project name
            const activeProjectName = document.querySelector(".project-link.active").dataset.project;
            getProjectId(activeProjectName);
        });
    });
};
function fetchTaskDetails(projectName) {
    // Make an AJAX request to fetch task details
    fetch('fetch_task_details.php?projectName=' + encodeURIComponent(projectName))
        .then(response => response.json())
        .then(tasks => {
            // Update the task table with fetched task details
            const taskTableBody = document.querySelector('.team-table tbody');
            taskTableBody.innerHTML = ''; // Clear existing rows
            if(tasks.length === 0){
                // If no tasks are assigned, display a message
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td colspan="3">No tasks assigned</td>
                `;
                taskTableBody.appendChild(row);
            } else {
                tasks.forEach(task => {
                const row = document.createElement('tr');
                row.innerHTML = `
                <td>${task.assigned_user ? task.assigned_user : 'Unassigned'}</td>

                    <td>${task.num_tasks}</td>
                    <td>
                        <ul>
                            ${task.task_details.split('<br>').map(taskDetail => `
                                <li>${taskDetail.replace(':', ' -')}</li>
                            `).join('')}
                        </ul>
                    </td>
                `;
                taskTableBody.appendChild(row);
            });
        }
        })
        .catch(error => {
            console.error('Error fetching task details:', error);
        });
}

//Edit Project Modal
const editModal = document.querySelector(".editProject-modal");
const editOverlay = document.querySelector(".editProject-overlay");
const openEditModalBtn = document.querySelector("#EditProject");
const closeEditModalBtn = document.querySelector(".editProject-btn-close");

// Declaring variable to open edit modal
const openEditModal = function () {
    editModal.classList.remove("hidden");
    editOverlay.classList.remove("hidden");
};
// Declaring variable to close edit modal
const closeEditModal = function () {
    editModal.classList.add("hidden");
    editOverlay.classList.add("hidden");
    editProjectForm.reset(); 
};
// Declaring variable to validate edit modal
const validateEditProjectForm = function (event) {
    const projectName = document.getElementById("projectName1").value;
    const editDeadline = document.getElementById("editDeadline1").value;
    const teamLeader = document.getElementById("teamLeader1").value;
    const editDuration = document.getElementById("editDuration1").value;


    // Validate project name
    if (projectName.trim() === "") {
        alert("Please enter a project name.");
        return false;
    }

    // Validate deadline format
    const deadlineRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!editDeadline.match(deadlineRegex)) {
        alert("Please enter the deadline in the format YYYY-MM-DD.");
        return false;
    }
    // Validate if the deadline is a valid date
    const deadlineDate = new Date(editDeadline);
    if (isNaN(deadlineDate.getTime())) {
        alert("Please enter a valid date.");
        return false;
    }
    // Validate duration (if provided)
    if (editDuration!== "" && !Number.isInteger(parseInt(editDuration))) {
        alert("Duration must be a valid integer.");
        return false;
    }
    // Validate team leader selection
    if (teamLeader === "") {
        alert("Please choose a team leader.");
        return false;
    }

    // Get today's date
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set time to midnight to compare dates only

    // Parse the deadline date string to a Date object
    deadlineDate.setHours(0, 0, 0, 0); // Set time to midnight to compare dates only

    // Check if the deadline is a day ahead of today
    if (deadlineDate <= today) {
        alert("New deadline must be at least a day ahead of today.");
        return false;
    }

    return true;
};

const openEditProjectModal = function(projectName) {
    // Make an AJAX request to get the project ID based on the project name
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `getProjectID.php?projectName=${projectName}`, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const projectId = xhr.responseText;
            // Make another AJAX request to fetch project details
            const xhrEditProject = new XMLHttpRequest();
            xhrEditProject.open("POST", "editProject.php", true);
            xhrEditProject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhrEditProject.onreadystatechange = function () {
                if (xhrEditProject.readyState === 4 && xhrEditProject.status === 200) {
                    // Parse the JSON response
                    const responseData = JSON.parse(xhrEditProject.responseText);
                    // Populate the form fields with the fetched project details
                    document.getElementById("projectName1").value = responseData.projectName;
                    // document.getElementById("projectName").setAttribute ("value","Hello")
                    document.getElementById("editDeadline1").value = responseData.deadline;
                    document.getElementById("editDuration1").value = responseData.duration;
                    // Select the team leader
                    const teamLeaderSelect = document.getElementById("teamLeader1");
                    const teamLeaderOption = [...teamLeaderSelect.options].find(option => option.value === responseData.teamLeader);
                    if (teamLeaderOption) {
                        teamLeaderSelect.value = responseData.teamLeader;
                    }
                    // Open the edit project modal
                    openEditModal();
                }
            };
            // Send the project ID in the request body
            xhrEditProject.send("projectId=" + projectId);
        }
    };
    xhr.send();
};
// Event listener for opening edit modal
openEditModalBtn.addEventListener("click", function() {
    const activeProjectLink = document.querySelector(".project-link.active");
    if (activeProjectLink && activeProjectLink.parentElement.id === "archivedProjects") {
        alert("Editing project for archived projects is not allowed.");
    } else {
        const projectName = activeProjectLink.dataset.project;
        openEditProjectModal(projectName);
    }
});

// Function to reload project list 
function reloadProjectList(){
    $.ajax({
        url:"projectsList.php",
        type: "POST",
        success: function(data){
          $('#myProjects').html(data)
        //   console.log(data);
          reload();
          triggerFirstProjectLinkClick();
          closeProjectModal();
        },
        error: function(){
        //   console.log('Error')
        }
      });
}
// Event Listener for editing project form
document.getElementById("editProjectForm").addEventListener("submit", function(event) {
    event.preventDefault();
    if (validateEditProjectForm()) {
        const projectName = document.querySelector(".project-link.active").dataset.project; // Get the active project name
        // Make an AJAX request to get the project ID based on the project name
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `getProjectID.php?projectName=${projectName}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const projectId = xhr.responseText;
                    // Include the project ID in the form data
                    const formData = new FormData(editProjectForm);
                    formData.append("projectId", projectId);
                    formData.append('updateProject', true);
                    for (const keys of formData.keys()){
                        console.log(keys);
                    }
                    for (const values of formData.values()){
                        console.log(values);
                    }
                    // Continue with the form submission
                    const xhrEditProject = new XMLHttpRequest();
                    xhrEditProject.open("POST","updateProject.php", true);
                    xhrEditProject.onreadystatechange = function () {
                        if (xhrEditProject.readyState === 4) {
                            if (xhrEditProject.status === 200) {
                                const response = JSON.parse(xhrEditProject.responseText);
                                if (response.success) {
                                    // Reload the project list and trigger the click event for the first project
                                    reloadProjectList();
                                    triggerFirstProjectLinkClick();
                                    closeEditModal();
                                } else {
                                    // Error occurred while updating project details
                                    console.error("Error updating project details:", response.error);
                                }
                            } else {
                                console.error("Error:", xhrEditProject.statusText);
                            }
                        }
                    };
                    xhrEditProject.send(formData);
                } else {
                    console.error("Error:", xhr.statusText);
                }
            }
        };
        xhr.send();
    }
});


//Event Listener for closing edit modal
closeEditModalBtn.addEventListener("click", closeEditModal);
editOverlay.addEventListener("click", closeEditModal);
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !editModal.classList.contains("hidden")) {
        closeEditModal();
    }
});

// Open the create a project modal
const modal = document.querySelector(".modal");
const overlay = document.querySelector(".overlay");
const openModalBtn = document.querySelector("#CreateProject");
const closeModalBtn = document.querySelector(".btn-close");

// Declaring variable to open the create a project modal
const openModal = function () {
    modal.classList.remove("hidden");
    overlay.classList.remove("hidden");
    createProjectForm.reset(); 
};
// Declaring variable to close the create a project modal
const closeProjectModal = function () {
    modal.classList.add("hidden");
    overlay.classList.add("hidden");
    createProjectForm.reset(); 
};
// Declaring variable to validate the create a project modal
const validateProjectForm = function (event) {
    const projectName = document.getElementById("projectName").value;
    const projectDeadline = document.getElementById("projectDeadline").value;
    const teamLeader = document.getElementById("teamLeader").value;

    // Validate project name
    if (projectName.trim() === "") {
        alert("Please enter a project name.");
        return false;
    }

    // Validate deadline format
    const deadlineRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!projectDeadline.match(deadlineRegex)) {
        alert("Please enter the deadline in the format YYYY-MM-DD.");
        return false;
    }

    // Validate team leader selection
    if (teamLeader === "") {
        alert("Please choose a team leader.");
        return false;
    }

    // Get today's date
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set time to midnight to compare dates only

    // Parse the deadline date string to a Date object
    const deadlineDate = new Date(projectDeadline);
    deadlineDate.setHours(0, 0, 0, 0); // Set time to midnight to compare dates only

    // Check if the deadline is a day ahead of today
    if (deadlineDate <= today) {
        alert("Deadline must be at least a day ahead of today.");
        return false;
    }

    return true;
};

const createProjectForm = document.getElementById("createProjectForm");
// Performs the Create a project functionality
createProjectForm.addEventListener("submit", function(event) {
    event.preventDefault();
    if (validateProjectForm()) {
        const formData = new FormData(createProjectForm);
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "createProject.php", true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    $.ajax({
                        url:"projectsList.php",
                        type: "GET",
                        success: function(data){
                          $('#myProjects').html(data)
                          reload();
                          triggerFirstProjectLinkClick();
                          closeProjectModal();
                        },
                        error: function(){
                          console.log('Error')
                        }
                      });
                    // console.log(response);
                } else {
                    console.error("Error:", xhr.statusText);
                }
            }
        };
        xhr.send(formData);
    }
});

openModalBtn.addEventListener("click", openModal);
closeModalBtn.addEventListener("click", closeProjectModal);
overlay.addEventListener("click", closeProjectModal);
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !modal.classList.contains("hidden")) {
        closeProjectModal();
    }
});

// Open the create a task modal
const taskModal = document.querySelector(".task-modal");
const taskOverlay = document.querySelector(".task-overlay");
const openTaskModalBtn = document.querySelector("#CreateTask");
const closeTaskModalBtn = document.querySelector(".task-btn-close");

// Declaring the variable to open the create a task modal
const openTaskModal = function () {
    taskModal.classList.remove("hidden");
    taskOverlay.classList.remove("hidden");
    createTaskForm.reset(); 

};
// Declaring the variable to close the create a task modal
const closeTaskModal = function () {
    taskModal.classList.add("hidden");
    taskOverlay.classList.add("hidden");
    createTaskForm.reset(); 

};
// Declaring the variable to validate the create a task modal
const validateTaskForm = function (event) {
    const taskName = document.getElementById("taskName").value;
    const taskDescription = document.getElementById("taskDescription").value;
    const taskDeadline = document.getElementById("taskDeadline").value;
    const taskEstimatedTime = document.getElementById("taskEstimatedTime").value;

    console.log("Estimated Time:", taskEstimatedTime); // Debug statement


    // Function to validate if the input is a non-negative integer
    function validateTaskEstimatedTime(time) {
        // Check if the input is empty
        if (time.trim() === '') {
            return true; // Empty input is valid
        }
        
        // Regular expression to match non-negative integers
        var regex = /^\d+$/;
        
        // Test the input against the regular expression
        if (regex.test(time)) {
            // Check if the input is a non-negative integer
            if (parseInt(time) >= 0) {
                return true; // Non-negative integer
            } else {
                return false; // Negative integer
            }
        } else {
            return false; // Not a non-negative integer
        }
    }

    // Validate task name
    if (taskName.trim() === "") {
        alert("Please enter a task name.");
        event.preventDefault();
        return false;
    }

    // Validate deadline format
    const deadlineRegex = /^\d{4}-\d{2}-\d{2}$/;
    if (!taskDeadline.match(deadlineRegex)) {
        alert("Please enter the deadline in the format YYYY-MM-DD.");
        event.preventDefault();
        return false;
    }

    // Get today's date
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Set time to midnight to compare dates only

    // Parse the deadline date string to a Date object
    const deadlineDate = new Date(taskDeadline);
    deadlineDate.setHours(0, 0, 0, 0); // Set time to midnight to compare dates only

    // Check if the deadline is a day ahead of today
    if (deadlineDate <= today) {
        alert("Deadline must be at least a day ahead of today.");
        return false;
    }

    if (!validateTaskEstimatedTime(taskEstimatedTime)) {
        alert("Estimated time must be a non-negative integer or empty.");
        event.preventDefault();
        return false;
    }

    return true;
};

const createTaskForm = document.getElementById("createTaskForm");

createTaskForm.addEventListener("submit", function(event) {
    event.preventDefault();
    if (validateTaskForm()) {

        const projectName = document.querySelector(".project-link.active").dataset.project; // Get the active project name
        // Make an AJAX request to get the project ID based on the project name
        const xhr = new XMLHttpRequest();
        xhr.open("GET", `getProjectID.php?projectName=${projectName}`, true);
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    const projectId = xhr.responseText;
                    // Include the project ID in the form data
                    const formData = new FormData(createTaskForm);
                    formData.append("Project_ID", projectId);
                    // Continue with the form submission
                    const xhrCreateTask = new XMLHttpRequest();
                    xhrCreateTask.open("POST", "createTask.php", true);
                    xhrCreateTask.onreadystatechange = function () {
                        if (xhrCreateTask.readyState === 4) {
                            if (xhrCreateTask.status === 200) {
                                const response = JSON.parse(xhrCreateTask.responseText);
                                // console.log(xhrCreateTask.responseText);
                                // console.log(response);
                                $.ajax({
                                    url:"projectsList.php",
                                    type: "POST",
                                    success: function(data){
                                    $('#myProjects').html(data)
                                    // console.log(data);
                                    reload();
                                    triggerFirstProjectLinkClick();
                                    closeTaskModal();
                                    },
                                    error: function(){
                                        console.log('Error')
                                    }
                                });
                            } else {
                                console.error("Error:", xhrCreateTask.statusText);
                            }
                        }
                    };
                    xhrCreateTask.send(formData);
                } else {
                    console.error("Error:", xhr.statusText);
                }
            }
        };
        xhr.send();
    };
});
openTaskModalBtn.addEventListener("click", function() {
    const activeProjectLink = document.querySelector(".project-link.active");
    if (activeProjectLink && activeProjectLink.parentElement.id === "archivedProjects") {
        alert("You cannot create tasks for an archived project.");
    } else {
        openTaskModal();
    }
});

// openTaskModalBtn.addEventListener("click", openTaskModal);
closeTaskModalBtn.addEventListener("click", closeTaskModal);
taskOverlay.addEventListener("click", closeTaskModal);
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !taskModal.classList.contains("hidden")) {
        closeTaskModal();
    }
});
// Open the delete project modal
const deleteModal = document.querySelector(".delete-modal");
const deleteOverlay = document.querySelector(".delete-overlay");
const openDeleteModalBtn = document.querySelector("#DeleteProject");
const closeDeleteModalBtn = document.querySelector(".delete-btn-close");

// Declaring the variable to open the delete a project modal
const openDeleteModal = function () {
    deleteModal.classList.remove("hidden");
    deleteOverlay.classList.remove("hidden");
};
// Declaring the variable to close the delete a project modal
const closeDeleteModal = function () {
    deleteModal.classList.add("hidden");
    deleteOverlay.classList.add("hidden");
};

openDeleteModalBtn.addEventListener("click", openDeleteModal);
closeDeleteModalBtn.addEventListener("click", closeDeleteModal);
deleteOverlay.addEventListener("click", closeDeleteModal);
document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && !deleteModal.classList.contains("hidden")) {
        closeDeleteModal();
    }
});
// Confirm delete button action
const confirmDeleteBtn = document.querySelector("#confirmDeleteBtn");
confirmDeleteBtn.addEventListener("click", function () {
    const projectName = document.querySelector(".project-link.active").dataset.project; // Get the active project name
    // Make an AJAX request to get the active project ID
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `getProjectID.php?projectName=${projectName}`, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                const projectId = xhr.responseText;
                // Make an AJAX request to delete the project
                const deleteXhr = new XMLHttpRequest();
                deleteXhr.open("POST", "deleteProject.php", true);
                deleteXhr.setRequestHeader("Content-Type", "application/json");

                // Define the data to send to the server
                const data = {
                    projectId: projectId,
                };

                // Define the callback function to handle the AJAX response
                deleteXhr.onreadystatechange = function () {
                    if (deleteXhr.readyState === 4) {
                        if (deleteXhr.status === 200) {
                            // Project deleted successfully
                            console.log("Project deleted!");
                            closeDeleteModal();
                            $.ajax({
                                url: "projectsList.php",
                                type: "GET",
                                success: function(data) {
                                    $('#myProjects').html(data);
                                    // console.log(data);
                                    console.log("Project list reloaded");
                                    reload();
                                    triggerFirstProjectLinkClick();
                                },
                                error: function() {
                                    console.error("Error reloading project list");
                                }
                            });
                            // Optionally, you can update the UI or perform additional actions
                        } else {
                            // Error occurred while deleting the project
                            console.error("Error deleting project:", deleteXhr.responseText);
                            // Optionally, display an error message to the user
                        }
                    }
                };

                // Convert the data object to JSON and send it with the request
                deleteXhr.send(JSON.stringify(data));
            } else {
                console.error("Error:", xhr.statusText);
            }
        }
    };
    xhr.send();
});

// Function to fetch chart data based on project ID
const fetchChartData = function(projectId) {
    // Make an AJAX request to fetch chart data
    const xhrChart = new XMLHttpRequest();
    xhrChart.open("GET", `fetchChartData.php?projectId=${projectId}`, true);
    xhrChart.onreadystatechange = function () {
        if (xhrChart.readyState === 4 && xhrChart.status === 200) {
            // Parse the JSON response
            const chartData = JSON.parse(xhrChart.responseText);
            // Process the chart data and create the chart
            createChart(chartData);
        }
    };
    // Send the request to fetch chart data
    xhrChart.send();
};

// Function to get the project ID based on project name
const getProjectId = function(projectName) {
    // Make an AJAX request to get the project ID based on the project name
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `getProjectID.php?projectName=${projectName}`, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const projectId = xhr.responseText;
            // Once the project ID is obtained, fetch chart data using the project ID
            fetchChartData(projectId);
        }
    };
    // Send the request to get the project ID
    xhr.send();
};

const createChart = function(chartData) {
    // Destroy existing chart if it exists
    if (window.donutChart instanceof Chart) {
        window.donutChart.destroy();
    }

    // Check if the chart data contains a message
    if (chartData.message) {
        // Display a message instead of creating the chart
        const donutChartCanvas = document.getElementById('donutChart').getContext('2d');
        const message = chartData.message;
        donutChartCanvas.fillText(message, 50, 50); // Customize the position as needed
        return; // Exit the function
    }
    
    // Extract chart data
    const totalTasks = chartData.totalTasks;
    const completedTasks = chartData.completedTasks;

    // Calculate remaining tasks
    const remainingTasks = totalTasks - completedTasks;

    // Create the donut chart
    const donutChartCanvas = document.getElementById('donutChart').getContext('2d');
    window.donutChart = new Chart(donutChartCanvas, {
        type: 'doughnut',
        data: {
            labels: ['Completed Tasks', 'Remaining Tasks'],
            datasets: [{
                data: [completedTasks, remainingTasks],
                backgroundColor: ['rgba(255, 255, 204, 0.7)', 'rgba(243, 225, 107, 0.7)'],
                borderColor: ['rgba(204, 204, 153, 1)', 'rgba(191, 149, 63, 1)'],
                borderWidth: 1
                
            }]
        },
        options: {
            // Customize chart options as needed
        }
    });
};
// Setting Project as complete requests extra validation from the user
document.getElementById('CompleteProject').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    // Get the active project name
    const projectName = document.querySelector(".project-link.active").dataset.project;
    const activeProjectLink = document.querySelector(".project-link.active");
    if (activeProjectLink && activeProjectLink.parentElement.id === "archivedProjects") {
        alert("This Project is already marked as Completed.");
    }else{
        if (confirm(`Are you sure you want to complete/ archive the project ${projectName}?`)) {

    // Make an AJAX request to get the project ID based on the project name
    fetch(`getProjectID.php?projectName=${encodeURIComponent(projectName)}`)
        .then(response => response.text())
        .then(projectId => {
            // Make another AJAX request to update the project status
            fetch('update_project_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    projectId: projectId,
                    status: 'COMPLETE' // or 'Archived' based on your requirements
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update project status');
                }
                // Success message or further actions if needed
                console.log('Project status updated successfully');
                reloadProjectList();
            })
            .catch(error => {
                console.error('Error updating project status:', error);
            });
        })
        .catch(error => {
            console.error('Error fetching project ID:', error);
        });
    }
    else{
        console.log('Error');
    }
    }
});
