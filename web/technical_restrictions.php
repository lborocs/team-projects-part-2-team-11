<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topics</title>
    <link rel="stylesheet" type="text/css" href="styles_main2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<?php
// Check if the 'category' variable exists in the query string and is not empty
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category = htmlspecialchars($_GET['category']); // Sanitize the input to prevent XSS attacks
} else {
    $category = "defaultCategory"; // Fallback value if 'topic' is not set 
}
?>

    <script>
	$(document).ready(function() {
                let topics = [];
		const category = "<?php echo $category; ?>";
                $.ajax({
                    url: "topics.php",
                    type: "GET",
                    data: { category:category},
		    dataType: 'json',
                    success: function (insertedHtml) {
                        let len = insertedHtml.length;
                        for (let i = 0; i < len; i++) {
                            let name = insertedHtml[i].topic;
                            topics.push(name);
                            const x = document.createElement("div");
			    x.setAttribute("id",name);
	                    x.setAttribute("class","topics");
                            x.innerHTML = name;
                	// Make the div clickable
               		    x.style.cursor = "pointer"; // Change cursor on hover
               		    x.onclick = function() {
                   	    window.location.href = "postspage.php?category=" + encodeURIComponent(category) + "&topicname=" + encodeURIComponent(x.getAttribute("id")); // Redirect on click
               		    };
               		    document.getElementById("topicsContainer").appendChild(x);
                         }
                    },
                    error: function (e) {
                        console.log(e.message);
                    }
                });
            });
    </script>

    <script>
    function generateTopicId(topicName) {
        const words = topicName.split(' ');
        let id = '';

        if (words.length > 1) {
            // For multi-word topics, use the first letter of the first two or three words
            id = words.slice(0, 3).map(word => word[0]).join('').toUpperCase();
            id = id.substring(0, 3); // Ensure no more than 3 characters
        } else {
            // For a single-word topic, use the first, potentially middle, and last letters to form 2 or 3 letters
            const word = words[0];
            if (word.length === 1) {
                // If only one letter, use it as is
                id = word.toUpperCase();
            } else if (word.length === 2) {
                // If two letters, use both
                id = word.toUpperCase();
            } else {
                // Use the first, middle (and next if available for 3 letters), and last letters
                const middleIndex = Math.floor(word.length / 2);
                id = word[0] + (word.length > 2 ? word.substring(middleIndex - 1, middleIndex + 1) : word[middleIndex]);
                id = id.substring(0, 3).toUpperCase(); // Ensure no more than 3 characters
            }
        }

        return id;
    }
</script>
</script>    


<script>
 $(document).ready(function () {
            $("#createTopicForm").submit(function (event) {
                let post = $("#postContent").val();
                const topic =  $("#topicTitle").val();
                let category = "<?php echo $category; ?>";
                let topicid = generateTopicId(topic);
                let postno = 1;
                let title = $("#postTitle").val();
                console.log("value:", topic);
                event.preventDefault();
                $.ajax({
    			url: "createTopic.php",
    			type: "POST",
                //sessions needed here
    			data: {content: post, email: "olivia.rodriguez@makeitall.org.uk",postno:postno,topic: topic, category: category, topicid: topicid, title: title},
    			success: function(response) {
        			console.log("Success response:", response);
        			alert(response); // Or display this message in your HTML
				    
				    closePopup()
    			},
    			error: function(xhr, status, error) {
        			console.log("Error response:", xhr.responseText);
        			alert("Error: " + xhr.responseText); // Or display this message in your HTML
                    
    			} 
});;
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
            <div class="content" id="content1" style="display: none;">
            
                <h2>Manage Content</h2>
            </div>
            <div class="content" id="content2" style="display: none;">
                
                <h2>Productivity Content</h2>
            </div>
            <div class="content" id="knowledgeContent" style="display: contents;">
                <!-- Content for the 'Knowledge' tab -->
                <div id="topicsNavbar" class="topicsNavbarContent">
                    <div class="dropdown-container">
                        <select id="dropdownMenuPage">
                            <option value="page1">Page 1</option>
                            <option value="page2">Page 2</option>
                        </select>
                    </div>
                    <div  class="dropdown-container">
                        <select id="dropdownMenuFilter">
                            <option value="allPosts">All posts</option>
                            <option value="mostLiked">Most Liked</option>
                            <option value="recent">Recent</option>
                        </select>
                    </div>
                    <div id="search-container">
                        <form id="searchForm">
                            <div id="searchContent">
                                <input type="text" placeholder="Search..." name="search">
                                <button id="search-button" type="submit">
                                    <i class="fa fa-search" style="color: #ed992b;"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="addButtonContainer">
                        <button id="addButton" class="add-button" onclick="openPopup()">
                            <img src="images/add.png" alt="Add Icon">
                        </button>
                    </div>
                </div>
                <hr>
                <div class="topicHeader">
                    <h2 class="headerContent">TOPICS</h2>
                </div>
                <ul class="breadcrumbs">
                    <li class="breadcrumbs-item">
                        <a href="standard_index.html" class="breadcrumbs_link">Home</a>
                    </li>
                    <li class="breadcrumbs-item">
                        <a href="technical.php?category=tech" class="breadcrumbs_link--active"
                        breadcrumbs_link--active>
                        Technical</a>
                    </li>
                </ul>

                <div id="topicsContainer">
                                    </div>
                <div class="overlay" id="overlay"></div>
                <div class="popup" id="createTopicPopup">
                    <h2>Create Topic</h2>
                    <form id="createTopicForm">
                        <label for="postTitle">Topic Title:</label>
                        <input type="text" id="topicTitle" name="topicTitle" required style="width: 50vh;">
                        <br>
                        <label for="postTitle">Post Title:</label>
                        <input type="text" id="postTitle" name="postTitle" required style="width: 50vh;">
                        <textarea id="postContent" name="postContent" required style="height:40vh ;width:50vh">
                        </textarea>
                        <div  class="dropdown-container">
                            <select id="dropdownMenuTopic">
                                <option value="allPosts">Technical</option>
                                <option value="mostLiked">Non-Technical</option>
                            </select>
                        </div>
                        <div class="createTopicButton-container">
                            <button type="submit" id="createTopicButton">Create</button>
                            <button type="button" id="cancelTopicButton" onclick="closePopup()">Cancel</button>
                        </div>
                    </form>
                    <button class="closePopup" onclick="closePopup()">&times;</button>
                </div>
            </div>
            <div class="content" id="content4" style="display: none;">
               
                <h2>Other Content</h2>
            </div>
            <div class="content" id="content5" style="display: none;">
           
                <h2>Other2 Content</h2>
            </div>
        </div>
    </div>
    <!-- section for footer -->
    <div class="footer">
        <p><u>makeitall.co.uk Acceptable Use Policy</u></p>
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

    function positionArrow() {
        const activeTab = document.querySelector('.tab.active');
        if (activeTab) {
            const tabRect = activeTab.getBoundingClientRect();
            const arrowWidth = arrow.offsetWidth;
            arrow.style.left = `${tabRect.left + (activeTab.offsetWidth - arrowWidth) / 1.7}px`;
        }
    }

    // tabs.forEach((tab, index) => {
    //     tab.addEventListener('click', function () {
    //         tabs.forEach(t => t.classList.remove('active'));
    //         this.classList.add('active');
    //         contents.forEach(content => (content.style.display = 'none'));
    //         contents[index].style.display = 'block';
    //         arrow.style.display = 'block';
    //         positionArrow();
    //     });
    // });
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

    function openPopup() {
        document.getElementById('overlay').style.display = 'block';
        document.getElementById('createTopicPopup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('createTopicPopup').style.display = 'none';
    }

</script>

</body>
</html>