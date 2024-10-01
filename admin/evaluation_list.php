<?php
include "../connection/conn.php";

// Get semester ID and name from URL parameters
$semester_id = isset($_GET['semester_id']) ? intval($_GET['semester_id']) : 0;
$semester_name = isset($_GET['semester_name']) ? urldecode($_GET['semester_name']) : '';

if (!$semester_id) {
    die("Invalid semester ID");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Teachers for <?php echo htmlspecialchars($semester_name); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --background-color: #ecf0f1;
            --text-color: #34495e;
            --success-color: #2ecc71;
            --error-color: #e74c3c;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-out;
        }

        h1, h2, h3 {
            color: var(--secondary-color);
            margin-bottom: 20px;
        }

        form {
            display: grid;
            gap: 20px;
            margin-bottom: 30px;
        }

        label {
            font-weight: 500;
            margin-bottom: 5px;
            display: block;
        }

        select, input[type="submit"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' fill='%23333' viewBox='0 0 12 12'%3E%3Cpath d='M10.293 3.293 6 7.586 1.707 3.293A1 1 0 0 0 .293 4.707l5 5a1 1 0 0 0 1.414 0l5-5a1 1 0 1 0-1.414-1.414z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
        }

        input[type="submit"] {
            background-color: var(--primary-color);
            color: white;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: slideUp 0.5s ease-out;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-color);
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tr:hover {
            background-color: #e6e6e6;
            transition: background-color 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .success-message, .error-message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-out;
        }

        .success-message {
            background-color: var(--success-color);
            color: white;
        }

        .error-message {
            background-color: var(--error-color);
            color: white;
        }

        .actions a {
            text-decoration: none;
            color: var(--primary-color);
            margin-right: 10px;
            transition: color 0.3s ease;
        }

        .actions a:hover {
            color: var(--secondary-color);
        }

        .back-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--secondary-color);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .back-button:hover {
            background-color: #34495e;
        }

        .back-button i {
            margin-right: 5px;
        }

        .info-message {
            background-color: #e3f2fd;
            color: #0d47a1;
            border: 1px solid #bbdefb;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
            font-size: 18px;
            animation: fadeIn 0.5s ease-out;
        }

        .info-message i {
            font-size: 24px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .info-message p {
            margin: 0;
            display: inline-block;
            vertical-align: middle;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Manage Teachers for <?php echo htmlspecialchars($semester_name); ?></h1>
        
        <a href="semester.php" class="back-button"><i class="fas fa-arrow-left"></i> Back to Semesters</a>

        <?php
        // Fetch teachers for this semester
        $sql = "SELECT t.id, t.name, t.subject 
                FROM teachers t
                JOIN semester_teachers st ON t.id = st.teacher_id
                WHERE st.semester_id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $semester_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>Name</th><th>Subject</th><th>Actions</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                echo "<td class='actions'>
                        <a href='edit_teacher.php?id=" . $row['id'] . "&semester_id=" . $semester_id . "'><i class='fas fa-edit'></i> Edit</a>
                        <a href='remove_teacher.php?id=" . $row['id'] . "&semester_id=" . $semester_id . "' onclick='return confirm(\"Are you sure?\");'><i class='fas fa-trash-alt'></i> Remove</a>
                      </td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='info-message'>";
            echo "<i class='fas fa-info-circle'></i>";
            echo "<p>No teachers have been assigned to this semester yet. Use the form below to add teachers.</p>";
            echo "</div>";
        }

        // Add a form to add new teachers to this semester
        echo "<h3>Add Teacher to Semester</h3>";
        echo "<form action='add_teacher_to_semester.php' method='post'>";
        echo "<input type='hidden' name='semester_id' value='" . $semester_id . "'>";
        echo "<select name='teacher_id' required>";
        echo "<option value=''>Select a teacher</option>";

        // Fetch all teachers not yet in this semester
        $sql = "SELECT id, name FROM teachers WHERE id NOT IN (SELECT teacher_id FROM semester_teachers WHERE semester_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $semester_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['name']) . "</option>";
        }

        echo "</select>";
        echo "<input type='submit' value='Add Teacher'>";
        echo "</form>";

        $conn->close();
        ?>
    </div>
</body>
</html>