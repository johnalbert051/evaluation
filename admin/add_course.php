<?php
require_once '../connection/conn.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'debug' => []];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $name = $_POST['name'] ?? '';
    $code = $_POST['code'] ?? '';
    $instructor = $_POST['instructor'] ?? '';
    $semester = $_POST['semester'] ?? ''; // Changed from semester_id to semester
    $status = $_POST['status'] ?? '';

    $response['debug']['received_data'] = $_POST;

    if (empty($name) || empty($code) || empty($instructor) || empty($semester) || empty($status)) {
        throw new Exception('All fields are required');
    }

    // Insert the course into the database
    $sql = "INSERT INTO course (course_name, course_code, instructor, semester, status) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("sssss", $name, $code, $instructor, $semester, $status);

    if (!$stmt->execute()) {
        throw new Exception('Error executing statement: ' . $stmt->error);
    }

    $response['success'] = true;
    $response['message'] = 'Course added successfully';

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