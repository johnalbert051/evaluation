<?php
session_start();
include "../connection/conn.php";

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if (isset($_POST['id'])) {
    $studentId = intval($_POST['id']);
    
    $stmt = $conn->prepare("DELETE FROM student_table WHERE id = ?");
    $stmt->bind_param("i", $studentId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Student deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting student: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Student ID not provided']);
}

$conn->close();