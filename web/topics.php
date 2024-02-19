<?php
$category=$_GET['category'];

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

// Write a SQL query to select data from a table
if($category=='tech'){
	$sql = "SELECT DISTINCT topic,category FROM Posts WHERE category LIKE 'tech';";
}else{
	$sql = "SELECT DISTINCT topic,category FROM Posts WHERE category LIKE 'non-tech';";
}


// Execute the query and get the result object
$result = mysqli_query($conn, $sql);
$allData=array();

// Check if the query returned any rows or not
if (mysqli_num_rows($result) > 0) {
  // Loop through the result object and fetch each row as an associative array
  while($row = mysqli_fetch_array($result,MYSQLI_ASSOC)) {
    // Display the data from each row using the column names as keys
   	$allData[]=$row;  
	}
}
// Close the connection
echo json_encode($allData);
mysqli_close($conn);
?>
