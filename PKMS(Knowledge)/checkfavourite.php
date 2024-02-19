<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$email = $_GET['email'];
$topicid = $_GET['topic_ID'];
$postnum = $_GET['postnum'];

$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

$sql = "SELECT * FROM Favourites WHERE user_email = ? AND Topic_ID = ? AND Postnum = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => 'Error preparing statement: ' . $conn->error]));
}

$stmt->bind_param("ssi", $email, $topicid, $postnum);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $response = ['status' => 'heart'];
} else {
    $response = ['status' => 'noheart'];
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
