 $('#logoutB').click(function (){
    window.location.href = "/signin.php";
});

function displayManager(role){
    if (role != "Manager"){
        $('#ManageButton').css('display','none');
    }else {
        $('#ManageButton').css('display','block');
    }          
}
displayManager(role);

//manger page essensials
$('#ManageButton').click(function (){
    window.location.href = "/PKMS(Manager)/ManagerLanding.php";
});

//manager page essensials
$('#ProdButton').click(function (){
    window.location.href = "/PKMS(Productivity)/ProductivityLanding.php";
});

//manger page essensials
$('#KnowledgeButton').click(function (){
    window.location.href = "/PKMS(Knowledge)/standard_index.php";
});

$('#RemindersButton').click(function (){
    window.location.href = "/PKMS(Knowledge)/remindersLanding.php";
});

$('#InviteButton').click(function (){
    window.location.href = "/PKMS(Knowledge)/invite.php";
});