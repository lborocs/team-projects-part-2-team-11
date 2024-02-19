<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$email = $_GET['email'];
$topicID = $_GET['topic_ID'];
$postNum = $_GET['postnum'];

$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    http_response_code(500);
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

$sql = "SELECT * FROM Posts_Updates WHERE user_email = ? AND Topic_ID = ? AND Postnum = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    die(json_encode(['error' => 'Error preparing statement: ' . $conn->error]));
}

$stmt->bind_param("ssi", $email, $topicID, $postNum);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
$conn->close();

if ($result->num_rows > 0) {
    echo json_encode(['status' => 'update']);
} else {
    echo json_encode(['status' => 'noUpdate']);
}
?>