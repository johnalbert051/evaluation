<?php
include '../connection/conn.php';

// Check if a search term is provided
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Prepare the SQL query
$sql = "SELECT * FROM course";
if (!empty($search)) {
    $sql .= " WHERE course_name LIKE ? OR course_code LIKE ? OR instructor LIKE ?";
}

// Prepare and execute the statement
$stmt = $conn->prepare($sql);
if (!empty($search)) {
    $searchParam = "%$search%";
    $stmt->bind_param("sss", $searchParam, $searchParam, $searchParam);
}
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Fetch all courses
$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}

// Close the statement and connection
$stmt->close();
$conn->close();

// Return the courses as JSON
header('Content-Type: application/json');
echo json_encode($courses);