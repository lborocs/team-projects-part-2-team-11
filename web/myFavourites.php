<?php
session_start();

$email = $_SESSION['email'];
$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles_main2.css" > 
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	
	<script>
    var email = '<?php echo $email?>';

	// Function to create a post element in the DOM
function createPostElement(id, title, content, email, topic, date) {
    // Create a new div for the post
    const postDiv = document.createElement("div");
    postDiv.setAttribute("id", id);
    postDiv.setAttribute("class", "posts");

    // Create a heading element for the topic and add it to the post div
    const topicElement = document.createElement("h4");
    topicElement.innerHTML = topic;
    postDiv.appendChild(topicElement);

    // Create a heading element for the title and add it to the post div
    const titleElement = document.createElement("h5");
    titleElement.innerHTML = title;
    postDiv.appendChild(titleElement);

    // Create a div for the post content and add it to the post div
    const contentDiv = document.createElement("div");
    contentDiv.setAttribute("class", "posts");
    contentDiv.innerHTML = content;
    postDiv.appendChild(contentDiv);

    // Make the post div clickable with a pointer cursor on hover
    postDiv.style.cursor = "pointer";

    // Create a div for the button container
    const buttonContainerDiv = document.createElement("div");
    buttonContainerDiv.className = "button-container";

    // Create a like button with an event listener for clicking
    const likeButton = document.createElement("button");
    likeButton.id = id + "LikeButton";
    likeButton.className = "like-button";
    likeButton.addEventListener('click', function () {
        favouritePost(this);
    });

    // Create an image element for the like button
    const likeImg = document.createElement("img");
    likeImg.src = "images/heart.png";
    likeImg.alt = "like Icon";

    // Append the image to the like button
    likeButton.appendChild(likeImg);

    // Append the like button to the button container div
    buttonContainerDiv.appendChild(likeButton);

    // Append the button container div to the post div
    postDiv.appendChild(buttonContainerDiv);

    // Append the post div to the post container in the DOM
    document.getElementById("postContainer").appendChild(postDiv);
}

    </script>

<script>
	$(document).ready(function() {
		let pagenum= 1;
                $.ajax({
                    url: "showfavourites.php",
                    type: "GET",
                    data: { topic:"all", email:email,filter:"default",pagenum:pagenum},
		            dataType: 'json',
                    success: function (postsData) {
                        posts=postsData.posts;
                        for (let i = 0; i <posts.length; i++) {
                            let content = posts[i].content;
                            let id = posts[i].Topic_ID +"-"+ posts[i].PostNo +"-";
                            let title = posts[i].title;
                            let date = posts[i].date_updated;
                            let uemail = posts[i].user_email;
                            let topic = posts[i].topic;
                            createPostElement(id, title, content,uemail,topic,date,)
                                    }
                        let result = postsData.totalFavourites / 5;
                        let pages = Math.ceil(result);
                        for (let a = 1; a < pages+1; a++) {
                            const page = document.createElement("option");
                            page.setAttribute("id", page + a);
                            page.setAttribute("value", a);
                            page.innerHTML = "Page " + a;
                            document.getElementById("dropdownMenuPage").appendChild(page);
                        }
                    },
                    error: function (e) {
                        console.log(e.message);
                    }
                });
            });
</script>

<script>
    function fetchAndUpdateMyPosts(pagenum) {
    let filter="default";
    $.ajax({
        url: "showfavourites.php",
        type: "GET",
        data: { topic:"all", email:email,filter:"default",pagenum:pagenum},
        dataType: 'json',
        success: function (postsData) {
            document.getElementById("postContainer").innerHTML = "";
            posts=postsData.posts;
            for (let i = 0; i <posts.length; i++) {
                let content = posts[i].content;
                let id = posts[i].Topic_ID +"-"+ posts[i].PostNo +"-";
                let title = posts[i].title;
                let date = posts[i].date_updated;
                let uemail = posts[i].user_email;
                let topic = posts[i].topic;
                createPostElement(id, title, content,uemail,topic,date);
            }
            let result = postsData.totalFavourites / 5;
            let pages = Math.ceil(result);
            document.getElementById("dropdownMenuPage").innerHTML = ""; // Clear existing options
            for (let a = 1; a < pages+1; a++) {
                const page = document.createElement("option");
                page.setAttribute("id", "Page" + a);
                page.setAttribute("value", a);
                page.innerHTML = "Page " + a;
                document.getElementById("dropdownMenuPage").appendChild(page);
                if(a == pagenum) { // Set the selected page number
                    page.selected = true;
                }
            }
        },
        error: function (e) {
            console.log(e.message);
        }
    });
}

</script>

<script>
// Now, define your favouritePost function to accept the element as its first argument
function favouritePost(element) {
    // You can access the ID of the element with element.id
   g.uk";
    let parts = element.id.split("-");
    let topic_ID = parts[0];
    let postnum = parts[1];
    let pagenum = document.getElementById('dropdownMenuPage').value;
    console.log("topic id:", topic_ID);
    $.ajax({
        url: "favourite.php",
        type: "GET",
        data: { email: email, topic_ID: topic_ID, postnum: postnum },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'removed') {
                fetchAndUpdateMyPosts(pagenum);
            }
        },
        error: function (e) {
            console.log("Error:", e.responseText); // Note: Changed to e.responseText for more detailed error information
        }
    });
}

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectElement = document.getElementById('dropdownMenuPage');
        let filter="default";
        selectElement.addEventListener('change', function() {
        let pagenum= this.value;
        console.log(pagenum);
        fetchAndUpdateMyPosts(pagenum);
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
            <button id = "ManageButton" class="tab">
                <img src="images/manage.png" alt="Manage Icon" height="20px" width="20px">
                Manage</button>
            <button id = "ProdButton" class="tab">
                <img src="images/productivity.png" alt="Productivity Icon" height="30px" width="30px">
                Productivity</button>
            <button id = "KnowledgeButton" class="tab">
                <img src="images/knowledge.png" alt="Knowledge Icon" height="20px" width="20px">
                Knowledge</button>
            <button id = "RemindersButton" class="tab">
                <img src="images/reminder.png" alt="Reminder Icon" height="20px" width="20px">
                Reminders</button>
            <button id = "InviteButton" class="tab">
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
                        <h4><?php
                            //set up the connection to the data base
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
                            
                            $email = $_SESSION['email'];
                            $role = $_SESSION['role'];

                            $usernameQuery = "SELECT username
                            FROM `Users_Details`
                            WHERE user_email = '$email'";

                            $username = mysqli_query($conn, $usernameQuery);

                            if (mysqli_num_rows($username) > 0){
                                //output data of each row
                                while ($row = mysqli_fetch_array($username)){
                                    echo "Welcome ".$row[0];
                                }
                            }

                        ?></h4>
                    </div>
                    <hr>
                    
                    <a id =  "logoutB" href="#" class="sub-menu-link">
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
        <!-- <div class="arrow"></div>
        <div class="tabs">
            <button class="tab">
                <img src="images/productivity.png" alt="Productivity Icon" height="30px" width="30px">
                Productivity</button>
            <button class="tab">
                <img src="images/knowledge.png" alt="Knowledge Icon" height="20px" width="20px">
                Knowledge</button>
            <button class="tab">
                <img src="images/manage.png" alt="Manage Icon" height="20px" width="20px">
                Manage</button>
            <button class="tab">
                <img src="images/reminder.png" alt="Reminder Icon" height="20px" width="20px">
                Reminders</button>
            <button class="tab">
                <img src="images/invite.png" alt="Invite Icon" height="20px" width="20px">
                Invite</button>

        </div> -->
        <div class="white-div">
            <div class="content" id="content1" style="display: none;">
                <!-- Content for the 'Productivity' tab Productivity Content-->
                <h2>Productivity Content</h2>
            </div>
            <div class="content" id="knowledgeContent" style="display: block;">
                <!-- Content for the 'Knowledge' tab -->
                <div id="myPostsNavbar" class="myPostsNavbarContent">
                     <div class="dropdown-container">
                         <select id="dropdownMenuPage">
                        </select>
                    </div>
                </div>
                <hr> 
                <ul class="breadcrumbs">
                    <li class="breadcrumbs-item">
                        <a href="standard_index.php" class="breadcrumbs_link">Home</a>
                    </li>
                    <li class="breadcrumbs-item">
                        <a href="contentHub.php" class="breadcrumbs_link">My Hub</a>
                    </li>
                    <li class="breadcrumbs-item">
                        <a href="myFavourites.php" class="breadcrumbs_link--active" 
                        breadcrumbs_link--active>My Favourites</a>
                    </li>
                </ul>
                <div id="postContainer" class="posts"></div> 
            </div>
            <div class="content" id="content3" style="display: none;">
                <!-- Content for the 'Manage' tab -->
                <h2>Manage Content</h2>
            </div>
            <div class="content" id="content4" style="display: none;">
                <!-- Content for the 'Other' tab -->
                <h2>Other Content</h2>
            </div>
            <div class="content" id="content5" style="display: none;">
            <!-- Content for the 'Other2' tab -->
            <h2>Other2 Content</h2>
            </div>
        </div>
    </div>
    <!-- section for footer -->
    <div class="footer">
        <p><u>makeitall.co.uk Acceptable Use Policy</u></p>
    </div>
<script>
    let role = '<?php echo $role?>'; 
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

    function openPopup(){
        document.getElementById('overlay').style.display = 'block';
        document.getElementById().style.display = 'block';
    }

    function closePopup(){
        document.getElementById('overlay').style.display = 'none';
        document.getElementById().style.display = 'none';
    }

    $(document).ready(function (){
        $('#ManageContent').css('display','block');
        $('#KnowledgeButton').addClass("active");
        $('.arrow').css('display','block');
    });
</script>
<script src="/PKMS/link.js"></script>
</body>
</html>