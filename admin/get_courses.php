<?php
require_once '../connection/conn.php';

header('Content-Type: application/json');

$sql = "SELECT * FROM course";
$result = $conn->query($sql);

if ($result) {
    $courses = [];
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    echo json_encode(['success' => true, 'courses' => $courses]);
} else {
    echo json_encode(['success' => false, 'message' => 'Error fetching courses: ' . $conn->error]);
}

$conn->close();