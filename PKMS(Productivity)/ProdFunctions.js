//functions that will collect the specified user data
//make them usable for the the websites
var mains = document.getElementsByClassName("main");

//set the initial permissions and flags for the page on load up.
updateAssignedTo(null);
permission = 'false';
deleteable = 'false';
state = 'currentTasks';

////code for current tasks and Archived Tasks////
document.getElementById("CurrentTasksB").onclick = function() {
  permission = 'false';
  deleteable = 'false';
  state = 'currentTasks';
  updateAssignedTo(null);

  //hide all other 'main' divs
  Array.from(mains).forEach(element => {
      if (element.id != "CurTasks"){
        element.style.display = "none";
      }
    });
  document.getElementById("CurTasks").style.display = "block";
  $('#listOfTasks').load(document.URL +  ' #listOfTasks');
}

document.getElementById("ArchivedTasksB").onclick = function() {
  permission = 'false';
  deleteable = 'true';
  state = 'ArchTasks';

  //hide all other 'main' divs
  Array.from(mains).forEach(element => {
      if (element.id != "ArchTasks"){
        element.style.display = "none";
      }
    });
  document.getElementById("ArchTasks").style.display = "block";
  $('#listOfTasks_Arch').load(document.URL +  ' #listOfTasks_Arch');
}

////code for each project (not projects that I Lead).////
function getProjectTasks(id){
  var projID = id;
  $('#Project_ID').val(projID);
  permission = 'false';
  deleteable = 'false';
  state = 'project';

  //hide all other 'main' divs
  Array.from(mains).forEach(element => {
    if (element.id != "ProjectTasks"){
      element.style.display = "none";
    }
  });
  document.getElementById("ProjectTasks").style.display = "block";

  //load the contents for the project contents div.
  $.ajax({
    url:"ProjectsTasks.php",
    type: "POST",

    data:{
      Project_ID : projID,
      User : User_
    },
    success: function(data){
      $('#ProjectTasks .tasks').html(data)
    } ,
    error: function(e){
      console.log(e.message)
    }
  });
}

function getProjectTasks_Leader(id){
  var projID = id;
  permission = 'true';
  deleteable = 'true';
  state = 'leader';
  $all = '';

  $('#Project_ID').val(projID);
  updateAssignedTo(projID)

  //hide all the other divs
  Array.from(mains).forEach(element => {
    if (element.id != "LeadProjectTasks"){
      element.style.display = "none";
    }
  });
  document.getElementById("LeadProjectTasks").style.display = "block";

  //load the contents for the project i lead contents div.
  $.ajax({
    url:"ProjectsTasks_Leader.php",
    type: "POST",

    data:{
      Project_ID : projID,
      Filter : $all
    },
    success: function(data){
      $('#leadProjTasks').html(data);
    } ,
    error: function(e){
      console.log(e.message);
    }
  });
}

//// code for the create tasks modal ////
$(".createTasksBtn").click(openModal);
//open the create tasks modal
function openModal() {
  $('#createTaskModal').removeClass("hidden");
  $('.overlay').removeClass("hidden");

  $("#createTaskModal").css("display", "block");
  $("#Overlay").css("display", "block");
};

//open the task details modal
function openTaskModal() {
  $('#taskModal').removeClass("hidden");
  $('.overlay').removeClass("hidden");

  $("#taskModal").css("display", "block");
  $("#Overlay").css("display", "block");
};

//close all modals
function closeModal() {
  $('#createTaskForm').trigger("reset");

  $('.modal').addClass("hidden");
  $('.overlay').addClass("hidden");

  $(".modal").css("display", "none");
  $(".overlay").css("display", "none");
};

$(document).on("keydown", function (e) {
    if (e.key === "Escape" && !$('#createTaskModal').hasClass("hidden")) {
        closeModal();
    }
});

//// code to create task////
$('#SubmitB').on('click', function(){  

  // Validate task name
  if ($.trim($('#taskTitle').val()) == "") {
    alert("Please enter a Task name.");
    return false;
  }

  // Validate deadline format
  var deadlineRegex = /^([0-9]{2})?[0-9]{2}(-)(1[0-2]|0?[1-9])\2(3[01]|[12][0-9]|0?[1-9])$/
  var taskDeadline = $('#taskDeadline').val()
  if (!taskDeadline.match(deadlineRegex)) {
      alert("Please enter the deadline in the format YYYY-MM-DD.");
      return false;
  }

  // Get today's date
  const today = new Date();
  today.setHours(0, 0, 0, 0); // Set time to midnight to compare dates only

  // Parse the deadline date string to a Date object
  const deadlineDate = new Date($('#taskDeadline').val());
  deadlineDate.setHours(0, 0, 0, 0); // Set time to midnight to compare dates only

  // Check if the deadline is a day ahead of today
  if (deadlineDate <= today) {
      alert("Deadline must be at least a day ahead of today.");
      return false;
  }

  $.ajax({
    url:"CreateTask.php",
    type: "POST",

    data:{
      taskTitle : $('#taskTitle').val(),
      taskDescription : $('#taskDescription').val(),
      taskDeadline : $('#taskDeadline').val(),
      assignedTo : $('#assignedTo').val(),
      Project_ID : $('#Project_ID').val()
    },
    success: function(data){
      closeModal();
      updateArea(state);

      $('#createTaskForm').trigger("reset");
    } ,
  });
});

//update the assignedto section on the create task modal.
function updateAssignedTo(ID){
  $('#Project_ID').val(ID);
  $.ajax({
    url:"UserOptions.php",
    type: "POST",

    data:{
      Project_ID : $('#Project_ID').val(),
      User : User_
    },
    success: function(data){
      $('#assignedTo').html(data)
    },
    error: function(){
      console.log('Error')
    }
  });
}

////When a task is clicked it should show the tasks details////
//write function to get task details
function getTaskDetails(id){
  var taskID = id;
  openTaskModal();
  $('#Task_ID').val(id);

  $.ajax({
    url:"GetTaskDetails.php",
    type: "POST",
    data:{
      Task_ID : taskID,
      User : User_,
      Permission : permission,
      Deleteable : deleteable
    },
    success: function(data){
      $('#taskInfo').html(data)
    } ,
    error: function(e){
      console.log(e.message)
    }
  });
}

////updating a task////
function updateTask(taskID,string){
  $('#Update').val(string)
  console.log($('#Update').val());
  
  $.ajax({
    url:"UpdateTask.php",
    type: "POST",
    data:{
      Task_ID : taskID,
      Update : $('#Update').val(),

      taskTitle : $('#Title').val(),
      taskDescription : $('#Description').val(),
      taskDeadline : $('#Deadline').val(),
      taskStatus : $('#Status').val(),
      assignedTo : $('#assigned').val(),
      Project_ID : $('#Project_ID').val()
    },
    success: function(data){
      updateArea(state);
      closeModal();
      alert('Task Updated :)');
    },
  });
}

//when the task has been updated reload the page behind it.
function updateArea(state){
  if (state == "currentTasks"){
    $('#CurrentTasksB').click();
  } else if (state == "ArchTasks"){
    $('#ArchivedTasksB').click();
  } else if (state == "project"){
    getProjectTasks($('#Project_ID').val());
  } else if (state == "leader"){
    getProjectTasks_Leader($('#Project_ID').val());
  }
}


//// code to filter button.////
$('.filterB').click(function(){
  
  $.ajax({
    url:"ProjectsTasks_Leader.php",
    type: "POST",

    data:{
      Project_ID : $('#Project_ID').val(),
      Filter : $('#Filter').val()
    },
    success: function(data){
      $('#leadProjTasks').html(data)
    } ,
    error: function(e){
      console.log(e.message)
    }
  });
  
});

//// code to refresh the projects that i lead section.////
$('.refresh').click(function(){
  var all = '';
  $.ajax({
    url:"ProjectsTasks_Leader.php",
    type: "POST",

    data:{
      Project_ID : $('#Project_ID').val(),
      Filter : all
    },
    success: function(data){
      $('#leadProjTasks').html(data)
    } ,
    error: function(e){
      console.log(e.message)
    }
  });
});

//this is to highlight the link clicked on the navBar
$('.sidenav a').click(function (){
  $('.sidenav a').css("background-color","");
  $('.sidenav a').css("color","");

  $(this).css("background-color","orange");
  $(this).css("color","white");
})