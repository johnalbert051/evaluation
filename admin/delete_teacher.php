<?php
session_start();
include "../connection/conn.php";

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if (isset($_POST['id'])) {
    $teacherId = intval($_POST['id']);
    
    $stmt = $conn->prepare("DELETE FROM teachers WHERE id = ?");
    $stmt->bind_param("i", $teacherId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Teacher deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error deleting teacher: ' . $conn->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Teacher ID not provided']);
}

$conn->close();