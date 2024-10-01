<?php
session_start();
include "../connection/conn.php";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$sql = "SELECT id, name, major, email FROM student_table";
$result = $conn->query($sql);

$students = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
    echo json_encode(['success' => true, 'students' => $students]);
} else {
    echo json_encode(['success' => false, 'message' => 'No students found']);
}

$conn->close();