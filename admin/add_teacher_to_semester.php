<?php
include "../connection/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $semester_id = intval($_POST['semester_id']);
    $teacher_id = intval($_POST['teacher_id']);

    $sql = "INSERT INTO semester_teachers (semester_id, teacher_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $semester_id, $teacher_id);

    if ($stmt->execute()) {
        echo "<script>alert('Teacher added to semester successfully'); window.location.href='evaluation_list.php?semester_id=" . $semester_id . "';</script>";
    } else {
        echo "<script>alert('Error adding teacher to semester'); window.history.back();</script>";
    }

    $stmt->close();
    $conn->close();
}
?>