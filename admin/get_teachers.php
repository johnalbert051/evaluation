<?php
// Include your database connection
include "../connection/conn.php";

// Fetch teachers data
$sql = "SELECT id, name, subject, username FROM teachers";
$result = $conn->query($sql);

$teachers = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
    $response = array(
        "success" => true,
        "teachers" => $teachers
    );
} else {
    $response = array(
        "success" => false,
        "message" => "No teachers found"
    );
}

// Close the database connection
$conn->close();

// Set the response header to JSON
header('Content-Type: application/json');

// Output the JSON response
echo json_encode($response);
?>