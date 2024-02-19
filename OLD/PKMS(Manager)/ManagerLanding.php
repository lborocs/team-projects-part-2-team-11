<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="final_landing.css">
    <link rel="stylesheet" href="/PKMS/PKMS(Productivity)/ManagerDraft.css">
    <link rel="stylesheet" href="/PKMS/PKMS(Manager)/ManagerDraft.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <title> PKMS DashBoard</title>
</head>
<body>
    <!-- code for the black header div -->
    <div class="header">
        <div class="logo">
            <img src="images/companylogo.png">
        </div>
        <div class="tabs">
            <button id = "ManageButton" class="tab">
                <img src="images/manage.png" alt="Manage Icon" height="20px" width="20px">
                Manage</button>
            <button id = "ProdButton" class="tab">
                <img src="images/productivity.png" alt="Productivity Icon" height="30px" width="30px">
                Productivity</button>
            <button class="tab">
                <img src="images/knowledge.png" alt="Knowledge Icon" height="20px" width="20px">
                Knowledge</button>
            <button class="tab">
                <img src="images/reminder.png" alt="Reminder Icon" height="20px" width="20px">
                Reminders</button>
            <button class="tab">
                <img src="images/invite.png" alt="Invite Icon" height="20px" width="20px">
                Invite</button>
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
        <div class="white-div">
            
            <div class="content" id="ManageContent" style="display: none;">
                <!-- Content for the 'Knowledge' tab -->
                <?php include 'ManagerDraft2.php'; ?>
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
    // const arrow = document.querySelector('.arrow');
    const contents = document.querySelectorAll('.content'); // Get all content divs


    tabs.forEach((tab, index) => {
    tab.addEventListener('click', function () {
        tabs.forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        contents.forEach(content => content.classList.remove('active'));
        contents[index].classList.add('active');
        // arrow.style.display = 'block';
        });
    });

    $('#ManageButton').click(function (){
        $('#ManageContent').css('display','block');
    });


    // // Handle window resize event
    // window.addEventListener('resize', function () {
    //     if (arrow.style.display === 'block') {
    //         positionArrow();
    //     }
    // });

</script>

</body>
</html>
