<?php
include '../connection/conn.php';

$sql = "SELECT id, rating FROM teachers";
$result = $conn->query($sql);

$ratings = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $ratings[] = array(
            'id' => $row['id'],
            'rating' => floatval($row['rating'])
        );
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($ratings);