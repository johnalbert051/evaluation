<?php
require_once '../connection/conn.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $courseId = $_POST['id'] ?? '';

    if (empty($courseId)) {
        throw new Exception('Course ID is required');
    }

    // Delete the course from the database
    $sql = "DELETE FROM course WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("i", $courseId);

    if (!$stmt->execute()) {
        throw new Exception('Error executing statement: ' . $stmt->error);
    }

    if ($stmt->affected_rows === 0) {
        throw new Exception('No course found with the given ID');
    }

    $response['success'] = true;
    $response['message'] = 'Course deleted successfully';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    if (isset($conn)) {
        $conn->close();
    }
}

echo json_encode($response);