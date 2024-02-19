<?php
// $email = $_SESSION['email']; // Example manager email
//echo "$email";

$topic = $_GET['topic']; // Assuming 'topic' is passed via the query string
$email = $_GET['email'];
$filter = $_GET['filter'];
$pagenum = $_GET['pagenum'];
$limit = 5; // The maximum number of rows you want to retrieve
$offset = ($pagenum-1)*5 ; // Initialize a counter variable

error_log("Topic: $topic, Email: $email, Filter: $filter, PageNum: $pagenum");
$host = "localhost"; // The hostname of your database server
$username = "team011"; // The username of your database user
$password = "JAEWyfUXpzqank7scpWm"; // The password of your database user
$dbname = "team011"; // The name of your database

// Create a connection object using mysqli_connect function
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check if the connection was successful or not
if (!$conn) {
    echo "UNSUCCESSFUL";
    die("Connection failed: " . mysqli_connect_error());
}

if ($email == "none") {

		$sql = "SELECT Posts.Topic_ID,Posts.PostNo, Posts.content, Users_Details.username, Posts.title, Posts.date_updated,Posts.update_count 
				FROM Posts JOIN Users_Details ON Posts.user_email= Users_Details.user_email WHERE Posts.topic LIKE ? ORDER BY Posts.date_updated 
				LIMIT ? OFFSET ?";

		$stmt = $conn->prepare($sql);

		// Check if the statement was prepared correctly
		if (!$stmt) {
    			echo "Error preparing statement: " . $conn->error;
    			exit;
			}

		// Correctly use $topic instead of $topicname
		$stmt->bind_param("sii", $topic, $limit, $offset);
		$stmt->execute(); // Execute the statement

		$result = $stmt->get_result();  // Get the result
		$postsArray = []; // Initialize an array to hold the fetched data
	
} else {
	$sql = "SELECT Posts.Topic_ID,Posts.PostNo, Posts.content, Users_Details.username, Posts.title, Posts.date_updated,Posts.update_count 
			FROM Posts JOIN Users_Details ON Posts.user_email= Users_Details.user_email WHERE Posts.user_email LIKE ? ORDER BY Posts.date_updated 
			LIMIT ? OFFSET ?";

	$stmt = $conn->prepare($sql); 

	// Check if the statement was prepared correctly
	if (!$stmt) {
    		echo "Error preparing statement: " . $conn->error;
    		exit;
		}

	$stmt->bind_param("sii", $email, $limit, $offset);

	$stmt->execute(); // Execute the statement 

	$result = $stmt->get_result();  // Get the result
	$postsArray = []; // Initialize an array to hold the fetched data
}


// Fetch the data
//while ($row = $result->fetch_assoc()) {
//    $postsArray[] = $row; // Add each row to the array
//}

$totalRows = $result->num_rows; // Total number of rows available
$limit = 5; // The maximum number of rows you want to retrieve
$counter = ($pagenum-1)*5 ; // Initialize a counter variable

while ($row = $result->fetch_assoc()) {
    $postsArray[] = $row; // Add each row to the array
    $counter++; // Increment the counter by 1
    if ($counter == $limit) {
        break; // Exit the loop once the limit is reached
    }
}


$stmt->close(); // Close the statement

$response = [
    'totalRows' => $totalRows, // Include the total number of rows in the response
    'posts' => $postsArray, // Include the posts data
];

// Set header to indicate the response is JSON
header('Content-Type: application/json');

// Encode the array as JSON and output it
echo json_encode($postsArray);
?>