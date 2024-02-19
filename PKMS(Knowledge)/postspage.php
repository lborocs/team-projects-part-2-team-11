<?php
session_start();

$email = $_SESSION['email'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link rel="stylesheet" type="text/css" href="styles_main2.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    
    

<?php
$topic = isset($_GET['topicname']) ? $_GET['topicname'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';
// Use $topicName and $category as needed
?>

<script>
var email = '<?php echo $email?>';

function createPostElement(id, title, content,email,date,i) {
    				const postDiv = document.createElement("div");
    				postDiv.setAttribute("id", id);
    				postDiv.setAttribute("class", "posts");

    				const titleElement = document.createElement("h4");
    				titleElement.innerHTML = title;
    				postDiv.appendChild(titleElement);

    				const contentDiv = document.createElement("div");
    				contentDiv.setAttribute("class", "posts");
    				contentDiv.innerHTML = content;
    				postDiv.appendChild(contentDiv);
    				// Make the div clickable
               		    	postDiv.style.cursor = "pointer"; // Change cursor on hover

    				document.getElementById("posts").appendChild(postDiv);

    				const buttonContainerDiv = document.createElement("div");
    				buttonContainerDiv.className = "button-container";

    				const updateButton = document.createElement("button");
    				updateButton.id = id+"updateButton";
    				updateButton.className = "update-button";

                    updateButton.addEventListener('click', function() {
                        updatePost(this);
                    });

    				const likeButton = document.createElement('button');
    				likeButton.id = id+"likeButton";
    				likeButton.className ="like-button";

                        // Use addEventListener to attach the click event
                    likeButton.addEventListener('click', function() {
                           favouritePost(this);
                    });

                    isFavourite(likeButton);
                    isUpdate(updateButton);
				
				const user_email = document.createElement("p");
       				user_email.innerHTML = email;
    				buttonContainerDiv.appendChild(user_email);
				
				const dateelement = document.createElement("p");
       				dateelement.innerHTML = date;
    				buttonContainerDiv.appendChild(dateelement);

    				buttonContainerDiv.appendChild(updateButton);
    				buttonContainerDiv.appendChild(likeButton);

				document.getElementById("posts").appendChild(postDiv);
				postDiv.appendChild(buttonContainerDiv);
}

</script>

<script>
// Now, define your favouritePost function to accept the element as its first argument
function favouritePost(element) {
    // You can access the ID of the element with element.id
    console.log("Element ID:", element.id); // For debugging
    //sessions needed here
    //let email = "olivia.rodriguez@makeitall.org.uk";
    let parts = element.id.split("-");
    let topic_ID = parts[0];
    let postnum = parts[1];
    const likeImg = element.getElementsByTagName('img')[0];
    console.log("topic id:", topic_ID);
    $.ajax({
        url: "favourite.php",
        type: "GET",
        data: { email: email, topic_ID: topic_ID, postnum: postnum },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'added') {
                likeImg.src = "images/heart.png";
            } else if (response.status === 'removed') {
                likeImg.src = "images/unheart.png";
            }
        },
        error: function (e) {
            console.log("Error:", e.responseText); // Note: Changed to e.responseText for more detailed error information
        }
    });
}

</script>

<script>
    function isFavourite(element) {
        //sessions needed here
        //let email = "olivia.rodriguez@makeitall.org.uk";
        let parts = element.id.split("-");
        let topic_ID = parts[0];
        let postnum = parts[1];
        const likeButton = element;
        $.ajax({
        url: "checkfavourite.php",
        type: "GET",
        data: { email: email, topic_ID: topic_ID, postnum: postnum },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'heart') {
                const likeImg = document.createElement("img");
    			likeImg.src = "images/heart.png";
    			likeImg.alt = "heart icon";
                likeButton.appendChild(likeImg);
            } else if (response.status === 'noheart') {
                const likeImg = document.createElement("img");
    			likeImg.src = "images/unheart.png";
    			likeImg.alt = "heart icon";
                likeButton.appendChild(likeImg);
            }
        },
        error: function (e) {
            console.log("Error:", e.responseText); // Note: Changed to e.responseText for more detailed error information
        }
    });
    }

    </script>

<script>
    function updatePost(element) {
    // You can access the ID of the element with element.id
    console.log("Element ID:", element.id); // For debugging
    //sessions needed here
    //let email = "olivia.rodriguez@makeitall.org.uk";
    let parts = element.id.split("-");
    console.log(parts);
    let topic_ID = parts[0];
    console.log(parts[0]);
    let postnum = parts[1];
    console.log(parts[1]);
    const updateImg = element.getElementsByTagName('img')[0];
    console.log("topic id:", topic_ID);
    $.ajax({
        url: "update.php",
        type: "GET",
        data: { email: email, topic_ID: topic_ID, postnum: postnum },
        dataType: 'json',
        success: function (response) {
            if (response.status === 'added') {
                updateImg.src = "images/bell.png";
            } else if (response.status === 'removed') {
                updateImg.src = "images/noUpdate.png";
            }
        },
        error: function (e) {
            console.log("Error:", e.responseText); // Note: Changed to e.responseText for more detailed error information
        }
    });
}

    function isUpdate(element) {
        //sessions needed here
        //let email = "olivia.rodriguez@makeitall.org.uk";
        let parts = element.id.split("-");
        let topic_ID = parts[0];
        let postnum = parts[1];
        console.log(topic_ID);
        console.log(postnum);
        const updateButton = element;
        $.ajax({
            url: "checkUpdate.php",
            type: "GET",
            data: { email: email, topic_ID: topic_ID, postnum: postnum },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'update') {
                    const updateImg = document.createElement("img");
                    updateImg.src = "images/bell.png";
                    updateImg.alt = "Update icon";
                    updateButton.appendChild(updateImg);
                } else if (response.status === 'noUpdate') {
                    const updateImg = document.createElement("img");
                    updateImg.src = "images/noUpdate.png";
                    updateImg.alt = "No Update icon";
                    updateButton.appendChild(updateImg);
                }
            },
            error: function (e) {
                console.log("Error:", e.responseText); // Note: Changed to e.responseText for more detailed error information
            }
        });
    }
</script>

    <script>
	$(document).ready(function() {
        let posts = [];
		let postid = [];
		let titles = [];
		let email="none";
		let filter="default";
		let pagenum= 1;
		const topic = "<?php echo $topic; ?>";
		$.ajax({
                    url: "getMyPosts.php",
                    type: "GET",
                    data: { topic: topic, email:email,filter:filter,pagenum:pagenum},
		    dataType: 'json',
                    success: function (response) {
			let result = response.totalRows / 5;
			let pages = Math.ceil(result);
			for (let a = 1; a < pages+1; a++) {
				const page = document.createElement("option");
    				page.setAttribute("id", page + a);
    				page.setAttribute("value", a);
				page.innerHTML = "Page " + a;
				document.getElementById("dropdownMenuPage").appendChild(page);
			}
			
		     }, error: function (e) {
                        console.log(e.responseText);
                    }
                });
                $.ajax({
                    url: "posts.php",
                    type: "GET",
                    data: { topic: topic, email:email,filter:filter,pagenum:pagenum},
		    dataType: 'json',
                    success: function (postsData) {
                        for (let i = 0; i <postsData.length; i++) {
                            let content = postsData[i].content;
                            posts.push(content);

			    let id = postsData[i].Topic_ID +"-"+ postsData[i].PostNo +"-";
                            postid.push(id);
			    let title = postsData[i].title;
                            titles.push(title);
			    let date = postsData[i].date_updated;
			    let uname = postsData[i].username;
			    createPostElement(id, title, content,uname, date,i)
               		    }
                    },
                    error: function (e) {
                        console.log(e.message);
                    }
                });
            });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectElement = document.getElementById('dropdownMenuPage');
    let email="none";
    const topic = "<?php echo $topic; ?>";
    let filter="default";

    selectElement.addEventListener('change', function() {
	let pagenum= this.value;
	console.log({ topic, email, filter, pagenum });
         $.ajax({
        url: "posts.php",
        type: "GET",
        data: { topic: topic, email: email,pagenum:pagenum, filter:filter},
        dataType: 'json',
        success: function(postsData) {
		console.log(postsData);
            // Clear existing posts before appending new ones to avoid duplication
            document.getElementById("posts").innerHTML = "";
		 // Total number of rows available
            for (let i = 0; i < postsData.length; i++) {
                // Call 'createPostElement' for each post
                let id = postsData[i].Topic_ID +"-"+ postsData[i].PostNo +"-";
                let title = postsData[i].title;
                let date = postsData[i].date_updated;
                let content = postsData[i].content;   
                let uname = postsData[i].username;
			    createPostElement(id, title, content,uname, date,i)
            }
        },
       error: function(xhr, status, error) {
    console.error("Could not fetch posts:", xhr.statusText);
    alert("Error: " + xhr.statusText + "\nStatus: " + status + "\nError: " + error);
}    });
    });
});
</script>

<script>
function fetchAndUpdatePosts() {
    let email = "none"; // Assuming 'email' is a global or appropriately scoped variable
    const topic = "<?php echo $topic; ?>"; // Ensure PHP variable is accessible
    let filter="default";
    let pagenum= 1;

    $.ajax({
        url: "posts.php",
        type: "GET",
        data: { topic: topic, email: email,pagenum:pagenum, filter:filter },
        dataType: 'json',
        success: function(postsData) {
            // Assuming 'insertedHtml' is the updated list of posts
            // Clear existing posts before appending new ones to avoid duplication
            document.getElementById("posts").innerHTML = "";
            for (let i = 0; i < postsData.length; i++) {
                // Call 'createPostElement' for each post
                createPostElement(postsData[i].post_ID, postsData[i].title, postsData[i].content,postsData[i].username,postsData[i].date_updated, i);
            }
        },
        error: function (e) {
            console.error("Could not fetch posts:", e.message);
        }
    });
}
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
	<script>
        $(document).ready(function () {
        $("#createPostForm").submit(function (event) {
		let post = $("#postContent").val();
		const topic = "<?php echo $topic; ?>";
		let category = "<?php echo $category; ?>";
		let topicid = generateTopicId(topic);
		let title = $("#postTitle").val();
                event.preventDefault();
                $.ajax({
    			url: "create.php",
    			type: "POST",
                //sessions needed here
    			data: {content: post, email: "olivia.rodriguez@makeitall.org.uk",topic: topic, category: category, topicid: topicid, title: title},
    			success: function(response) {
        			console.log("Success response:", response);
        			alert(response); // Or display this message in your HTML
				    fetchAndUpdatePosts(document.getElementById("dropdownMenuPage").value);
				    closePopup()
    			},
    			error: function(xhr, status, error) {
        			console.log("Error response:", xhr.responseText);
        			
    			} 
});;
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            const topic = "<?php echo $topic; ?>";
            let category = "<?php echo $category; ?>";
            
            // Encode the category and topic for a URL
            let encodedCategory = encodeURIComponent(category);
            let encodedTopic = encodeURIComponent(topic);

            // Update breadcrumb link dynamically
            let newHref = "postspage.php?category=" + encodedCategory + "&topicname=" + encodedTopic;

            if (category === "tech") {
                $("#postBreadcrumbs-item a").text("Technical Posts");
                $("#postBreadcrumbs-item a").attr("href", newHref);
                $("#categoryBreadcrumbs-item a").text("Technical");
                $("#categoryBreadcrumbs-item a").attr("href", "technical.php?category=tech");
            } else if (category === "non-tech") {
                $("#postBreadcrumbs-item a").text("Non-Technical Posts");
                $("#postBreadcrumbs-item a").attr("href", newHref);
                $("#categoryBreadcrumbs-item a").text("Non-Technical");
                $("#categoryBreadcrumbs-item a").attr("href", "nonTechnical.php?category=non-tech");
            } else {
                $("#categoryBreadcrumbs-item a").text("Home");
                $("#categoryBreadcrumbs-item a").attr("href", "standard_index.php");
            }
        });
    </script>

<script>
$(document).ready(function() {
    $("#searchForm").submit(function(event) {
        event.preventDefault(); // Prevent the form from submitting through the browser
        var searchQuery = $('input[name="search"]').val();
        console.log("Search query:", searchQuery);
        // Here, you can call an AJAX function or any other function to handle the search
		let filter="none";
		const topic = "<?php echo $topic; ?>";
        document.getElementById("posts").innerHTML = "";
        document.getElementById("dropdownMenuPage").innerHTML = "";
				const page = document.createElement("option");
    			page.setAttribute("id", "Page 1");
    			page.setAttribute("value", 1);
				page.innerHTML = "Page 1" ;
				document.getElementById("dropdownMenuPage").appendChild(page);
                $.ajax({
                    url: "search.php",
                    type: "GET",
                    data: {topic:topic,category:"none",searchQuery:searchQuery},
		            dataType: 'json',
                    success: function (postsData) {
                        for (let i = 0; i <postsData.length; i++) {
                            let content = postsData[i].content;
                            let id = postsData[i].Topic_ID +"-"+ postsData[i].PostNo +"-";
                            let title = postsData[i].title;
                            let date = postsData[i].date_updated;
                            let uname = postsData[i].username;
                            createPostElement(id, title, content,uname,date,i);
               		  }
                    },
                    error: function (e) {
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
        <div class="white-div">
            <div class="content" id="knowledgeContent" style="display: contents;">
                <!-- Content for the 'Knowledge' tab -->
                <div id="postsNavbar" class="postsNavbarContent">
                    <div class="dropdown-container">
                        <select id="dropdownMenuPage">
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
                <ul class="postBreadcrumbs">
                    <li class="breadcrumbs-item">
                        <a href="standard_index.php" class="breadcrumbs_link">Home</a>
                    </li>
                    <li class="breadcrumbs-item" id="categoryBreadcrumbs-item">
                        <a class="breadcrumbs_link"></a>
                    </li>
                    <li class="breadcrumbs-item" id="postBreadcrumbs-item">
                        <a class="breadcrumbs_link--active"></a>
                    </li>
                </ul>

                <div class="postStructureContent">
                    <h2 class="postHeaderContent">POSTS</h2>
                    <div id="posts"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                <div class="popup" id="createPostPopup">
                    <h2>Create Post</h2>
                    <form id="createPostForm">
                        <label for="postTitle">Post Title:</label>
                        <input type="text" id="postTitle" name="postTitle" required style="width: 50vh;">
                        <textarea id="postContent" name="postContent" required style="heigh:50vh;width:50vh">
                        </textarea>
                        <div class="createPostButton-container">
                            <button type="submit" id="createButton">Create</button>
                            <button type="button" id="cancelButton" onclick="closePopup()">Cancel</button>
                        </div>
                    </form>
                    <button class="closePopup"onclick="closePopup()">&times;</button>
                </div>
            </div>
        </div>
    </div>
    <!-- section for footer -->
        <div class="footer">
            <p><u>makeitall.co.uk Acceptable Use Policy</u></p>
        </div>
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

    function openPopup() {
        document.getElementById('overlay').style.display = 'block';
        document.getElementById('createPostPopup').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('overlay').style.display = 'none';
        document.getElementById('createPostPopup').style.display = 'none';
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