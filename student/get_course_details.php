<?php
session_start();
include '../connection/conn.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo json_encode(['error' => 'Not authorized or missing course ID']);
    exit();
}

$course_id = $_GET['id'];

$query = "SELECT course_name, course_code, instructor,semester, status FROM course WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();

if ($course) {
    echo json_encode($course);
} else {
    echo json_encode(['error' => 'Course not found']);
}