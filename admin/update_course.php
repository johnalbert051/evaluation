<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once '../connection/conn.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'debug' => []];

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    $id = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $code = $_POST['code'] ?? '';
    $instructor = $_POST['instructor'] ?? '';
    $semester = $_POST['semester'] ?? '';
    $status = $_POST['status'] ?? '';

    $response['debug']['received_data'] = $_POST;

    if (empty($id) || empty($name) || empty($code) || empty($instructor) || empty($semester) || empty($status)) {
        throw new Exception('All fields are required');
    }

    $sql = "UPDATE course SET course_name = ?, course_code = ?, instructor = ?, semester = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if (!$stmt) {
        throw new Exception('Error preparing statement: ' . $conn->error);
    }

    $stmt->bind_param("sssssi", $name, $code, $instructor, $semester, $status, $id);

    if (!$stmt->execute()) {
        throw new Exception('Error executing statement: ' . $stmt->error);
    }

    $response['success'] = true;
    $response['message'] = 'Course updated successfully';
    $response['debug']['affected_rows'] = $stmt->affected_rows;
    $response['debug']['updated_semester'] = $semester;

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