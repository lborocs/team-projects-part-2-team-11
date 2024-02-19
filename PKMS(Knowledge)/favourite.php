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

$response = [];
if ($result->num_rows > 0) {
    $sql2 = "DELETE FROM Favourites WHERE user_email = ? AND Topic_ID = ? AND Postnum = ?";
    $stmt2 = $conn->prepare($sql2);
    if (!$stmt2) {
        die(json_encode(['error' => 'Error preparing delete statement: ' . $conn->error]));
    }
    $stmt2->bind_param("ssi", $email, $topicid, $postnum);
    if ($stmt2->execute()) {
        $response['status'] = 'removed';
    } else {
        $response['error'] = 'Failed to remove favorite';
    }
    $stmt2->close();
} else {
    $sql2 = "INSERT INTO Favourites (user_email, Topic_ID, Postnum) VALUES (?, ?, ?)";
    $stmt2 = $conn->prepare($sql2);
    if (!$stmt2) {
        die(json_encode(['error' => 'Error preparing insert statement: ' . $conn->error]));
    }
    $stmt2->bind_param("ssi", $email, $topicid, $postnum);
    if ($stmt2->execute()) {
        $response['status'] = 'added';
    } else {
        $response['error'] = 'Failed to add favorite';
    }
    $stmt2->close();
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>