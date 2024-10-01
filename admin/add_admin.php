<?php
include "../connection/conn.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert to admin_table
    $sql = "INSERT INTO admin_table (admin_name, username, password, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $username, $hashed_password, $role);
    
    if ($stmt->execute()) {
        $admin_id = $stmt->insert_id;
        echo json_encode([
            'success' => true,
            'admin' => [
                'id' => $admin_id,
                'name' => $name,
                'username' => $username
            ]
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => $stmt->error
        ]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}