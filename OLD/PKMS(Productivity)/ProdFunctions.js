//functions that will collect the specified user data
//make them usable for the the websites
var mains = document.getElementsByClassName("main");
User_ = 'frederick.umah@makeitall.org.uk';
updateAssignedTo(null);
permission = 'false';


////code for current tasks and Archived Tasks////
document.getElementById("CurrentTasksB").onclick = function() {
  permission = 'false';
  updateAssignedTo(null);

  Array.from(mains).forEach(element => {
      if (element.id != "CurTasks"){
        element.style.display = "none";
      }
    });
  document.getElementById("CurTasks").style.display = "block";
  //  $('#listOfTasks').load(document.URL +  ' #listOfTasks');
}

document.getElementById("ArchivedTasksB").onclick = function() {
  permission = 'false';
  Array.from(mains).forEach(element => {
      if (element.id != "ArchTasks"){
        element.style.display = "none";
      }
    });
  document.getElementById("ArchTasks").style.display = "block";
}

////code for each project////
function getProjectTasks(id){
  var projID = id;
  $('#Project_ID').val(projID);
  permission = 'false';

  Array.from(mains).forEach(element => {
    if (element.id != "ProjectTasks"){
      element.style.display = "none";
    }
  });
  document.getElementById("ProjectTasks").style.display = "block";

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

  $('#Project_ID').val(projID);
  updateAssignedTo(projID)

  Array.from(mains).forEach(element => {
    if (element.id != "LeadProjectTasks"){
      element.style.display = "none";
    }
  });
  document.getElementById("LeadProjectTasks").style.display = "block";

  $.ajax({
    url:"ProjectsTasks_Leader.php",
    type: "POST",

    data:{Project_ID : projID},
    success: function(data){
      $('#LeadProjectTasks .tasks').html(data)
    } ,
    error: function(e){
      console.log(e.message)
    }
  });
}

//// code for the create tasks model ////
$(".createTasksBtn").click(openModal);
function openModal() {
  $('#createTaskModal').removeClass("hidden");
  $('.overlay').removeClass("hidden");

  $("#createTaskModal").css("display", "block");
  $("#Overlay").css("display", "block");
};

function openTaskModal() {
  $('#taskModal').removeClass("hidden");
  $('.overlay').removeClass("hidden");

  $("#taskModal").css("display", "block");
  $("#Overlay").css("display", "block");
};

function closeModal() {
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
$('#SubmitB').click(function(){
  $.ajax({
    url:"createTask.php",
    type: "POST",

    data:{
      taskTitle : $('#taskTitle').val(),
      taskDescription : $('#taskDescription').val(),
      taskDeadline : $('#taskDeadline').val(),
      assignedTo : $('#assignedTo').val(),
      Project_ID : $('#Project_ID').val()
    },
    success: function(data){
      alert("Task Created - Refresh page to access the new task");
      closeModal();
    } ,
    complete : function(){
      $('#listOfTasks').load(document.URL +  ' #listOfTasks');
      $('#createTaskForm').trigger("reset");
      //updateAssignedTo(null)
    }
  });
  
});

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
    error: function(e){
      console.log('Error')
    }
  });
}

////When a task is created////
function getTaskDetails(id){
  var taskID = id;
  openTaskModal();
  $('#Task_ID').val(id);
  User_ = 'frederick.umah@makeitall.org.uk';

  $.ajax({
    url:"GetTaskDetails.php",
    type: "POST",
    data:{
      Task_ID : taskID,
      User : User_,
      Permission : permission
    },
    success: function(data){
      $('#taskInfo').html(data)
    } ,
    error: function(e){
      console.log(e.message)
    }
  });
}