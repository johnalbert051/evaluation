<?php
require_once '../connection/conn.php';

header('Content-Type: application/json');

$response = ['success' => false, 'course' => null, 'message' => '', 'debug' => []];

try {
    if (!isset($_GET['id'])) {
        throw new Exception('Course ID is required');
    }

    $courseId = intval($_GET['id']);
    $response['debug']['courseId'] = $courseId;

    $sql = "SELECT * FROM course WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("i", $courseId);

    if (!$stmt->execute()) {
        throw new Exception('Error executing statement: ' . $stmt->error);
    }

    $result = $stmt->get_result();
    $course = $result->fetch_assoc();

    $response['debug']['sql'] = $sql;
    $response['debug']['rowCount'] = $result->num_rows;
    $response['debug']['fetchedCourse'] = $course;

    if (!$course) {
        throw new Exception('No course found with the given ID');
    }

    $response['success'] = true;
    $response['course'] = $course;

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