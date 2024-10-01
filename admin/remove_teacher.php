<?php
include "../connection/conn.php";

// Check if the user is logged in and has admin privileges
// ... (add your authentication check here)

$teacher_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$semester_id = isset($_GET['semester_id']) ? intval($_GET['semester_id']) : 0;

if ($teacher_id && $semester_id) {
    // Prepare SQL to remove the teacher from the semester
    $sql = "DELETE FROM semester_teachers WHERE teacher_id = ? AND semester_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $teacher_id, $semester_id);
    
    if ($stmt->execute()) {
        // Successful removal
        $message = "Teacher successfully removed from the semester.";
    } else {
        // Failed to remove
        $message = "Error removing teacher: " . $conn->error;
    }
    
    $stmt->close();
} else {
    $message = "Invalid teacher or semester ID.";
}

$conn->close();

// Redirect back to the evaluation list with a message
header("Location: evaluation_list.php?semester_id=$semester_id&message=" . urlencode($message));
exit();
?>