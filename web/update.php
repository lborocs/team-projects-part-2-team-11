<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

$email = $_GET['email'];
$topicId = $_GET['topic_ID'];
$postNum = $_GET['postnum'];
// $updateCount = $_GET['update_count'];

$host = "localhost";
$username = "team011";
$password = "JAEWyfUXpzqank7scpWm";
$dbname = "team011";

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

// SQL to check if there's an existing post update by the user
$sql = "SELECT * FROM Posts_Updates WHERE user_email = ? AND Topic_ID = ? AND Postnum = ?";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    http_response_code(500);
    die(json_encode(['error' => 'Error preparing statement: ' . $conn->error]));
}

$stmt->bind_param("ssi", $email, $topicId, $postNum);
$stmt->execute();
$result = $stmt->get_result();

$response = [];
// Check if there's an existing record
if ($result->num_rows > 0) {
    $sql2 = "DELETE FROM Posts_Updates WHERE user_email = ? AND Topic_ID = ? AND Postnum = ?";
    $stmt2 = $conn->prepare($sql2);
    if (!$stmt2) {
        die(json_encode(['error' => 'Error preparing delete statement: ' . $conn->error]));
    }
    $stmt2->bind_param("ssi", $email, $topicId, $postNum);
    if ($stmt2->execute()) {
        $response['status'] = 'removed';
    } else {
        $response['error'] = 'Failed to remove update';
    }
    $stmt2->close();
} else {
    // If no record exists, prepare to insert a new one
    $sql2 = "INSERT INTO Posts_Updates (user_email, Topic_ID, Postnum) VALUES (?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    if (!$stmt2) {
        die(json_encode(['error' => 'Error preparing insert statement: ' . $conn->error]));
    }
    $stmt2->bind_param("ssi", $email, $topicId, $postNum);
    if ($stmt2->execute()) {
        $response['status'] = 'added';
    } else {
        $response['error'] = 'Failed to add update';
    }
    $stmt2->close();
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>