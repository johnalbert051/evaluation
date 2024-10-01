<?php
session_start();
include "../connection/conn.php";

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

if (isset($_POST['id'])) {
    $semesterId = intval($_POST['id']);
    
    // Start transaction if you need to delete from multiple tables
    $conn->begin_transaction();

    try {
        // Delete from related tables first (if necessary)
        $stmt = $conn->prepare("DELETE FROM semester_teachers WHERE semester_id = ?");
        $stmt->bind_param("i", $semesterId);
        $stmt->execute();

        $stmt = $conn->prepare("DELETE FROM semester WHERE id = ?");
        $stmt->bind_param("i", $semesterId);
        
        if ($stmt->execute()) {
            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Semester deleted successfully']);
        } else {
            throw new Exception($conn->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error deleting semester: ' . $e->getMessage()]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Semester ID not provided']);
}

$conn->close();