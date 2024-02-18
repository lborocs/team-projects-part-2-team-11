<?php
$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$emailResults = array(); 

$sql = "SELECT user_email FROM Users_Details WHERE role = 'Employee'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $emailResults[] = $row["user_email"]; 
    }
} else {
    echo "0 results";
}
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles_main2.css">
    <script src=" https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script>
        	$(document).ready(function () {
			$("#submit").click(function (event) {
				event.preventDefault();
            					$.ajax({
						url:"test.php",
						type: "GET",
						data:{},
						success:function(insertedHtml){
							$("#serverResponse2").html(insertedHtml);
						},
						error:function(e){
							console.log(e.message);
						}
					});
					
			});
        	});
	</script>
</head>
<body>
    <!-- code for the black header div -->
    <div class="header">
        <div class="logo">
            <img src="images/companylogo.png">
        </div>
        <div class="tabs">
            <button class="tab">
                <img src="images/manage.png" alt="Manage Icon" height="20px" width="20px">
                Manage</button>
            <button class="tab">
                <img src="images/productivity.png" alt="Productivity Icon" height="30px" width="30px">
                Productivity</button>
            <button class="tab">
                <img src="images/knowledge.png" alt="Knowledge Icon" height="20px" width="20px">
                Knowledge</button>
            <button class="tab">
                <img src="images/reminder.png" alt="Reminder Icon" height="20px" width="20px">
                Reminders</button>
            <button class="tab" onclick="window.location.href='invite.html';">
                <img src="images/invite.png" alt="Invite Icon" height="20px" width="20px"> Invite
            </button>
                
            <!-- <button class="tab">
                <img src="images/invite.png" alt="Invite Icon" height="20px" width="20px">
                Invite</button> -->
        </div>
        <div class="buttons">
            <button class="user-btn">
                <img src="images/user.png" class="user-pic" onclick="toggleMenu()">
            </button>
            <div class="sub-menu-wrap" id="subMenu">
                <div class="sub-menu">
                    <div class="user-info">
                        <img src="images/user.png">
                        <h4>James Aldrino</h4>
                    </div>
                    <hr>
                    <a href="#" class="sub-menu-link">
                    <img src="images/help.png">
                    <p>Help </p>
                    <span>></span>
                    </a>
                    <a href="#" class="sub-menu-link">
                    <img src="images/logout.png">
                    <p>logout</p>
                    <span>></span>
                    </a>
                </div>
            </div>
        </div>
    </div>
<!-- code for the MAIN CONTENT after header div -->
    <div class="main-content">
        <div class="white-div2">
        <div class="email-results-container">
        <h2>Email Addresses of Employees</h2>
        <?php if (!empty($emailResults)): ?>
        <table class="email-results-table">
            <tbody>
                <?php foreach ($emailResults as $email): ?>
                <tr>
                    <td><?php echo htmlspecialchars($email); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No employee emails found.</p>
        <?php endif; ?>
</div>
        
        </div>
    </div>
    <!-- section for footer -->
    <div class="footer">
        <p><u>Make It All</u> <u>Acceptable Use Policy </u></p>
    </div>
    <script>
    let subMenu = document.getElementById("subMenu");
    let userButton = document.querySelector(".user-btn");

    function toggleMenu() {
        subMenu.classList.toggle("open-menu");

        if (subMenu.classList.contains("open-menu")) {
            // Add event listener to detect clicks outside the submenu
            document.addEventListener('click', closeMenuOutside);
        } else {
            // If menu is closed, remove the click event listener
            document.removeEventListener('click', closeMenuOutside);
        }
    }

    function closeMenuOutside(event) {
        // Check if the clicked element is outside the user button and submenu
        if (!subMenu.contains(event.target) && !userButton.contains(event.target)) {
            subMenu.classList.remove("open-menu");
            // Remove the click event listener after closing the submenu
            document.removeEventListener('click', closeMenuOutside);
        }
    }

    const tabs = document.querySelectorAll('.tab');
    const arrow = document.querySelector('.arrow');
    const contents = document.querySelectorAll('.content'); // Get all content divs


    tabs.forEach((tab, index) => {
    tab.addEventListener('click', function () {
        if (index === 2) { 
            window.location.href = 'standard_index.html'; 
            return;
        } 
        if (index === 0) {
            window.location.href = 'manage.html';
            return;
        }
        if (index === 3) { 
            window.location.href = 'reminders.html'; 
            return;
        }
        if (index === 4) { 
            window.location.href = 'invite.html'; 
            return;
        }
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        contents.forEach(content => (content.style.display = 'none'));
        contents[index].style.display = 'block';
        arrow.style.display = 'block';
        positionArrow();
    });
});

    // Handle window resize event
    window.addEventListener('resize', function () {
        if (arrow.style.display === 'block') {
            positionArrow();
        }
    });

</script>

</body>
</html>
