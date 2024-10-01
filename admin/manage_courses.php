<?php
include "../connection/conn.php";

$semester_id = $_GET['semester_id'];

// Check if the courses table exists
$table_exists = $conn->query("SHOW TABLES LIKE 'courses'")->num_rows > 0;

if (!$table_exists) {
    die("The 'courses' table doesn't exist. Please run the database setup script.");
}

// Fetch semester details
$stmt = $conn->prepare("SELECT * FROM semester WHERE id = ?");
$stmt->bind_param("i", $semester_id);
$stmt->execute();
$semester = $stmt->get_result()->fetch_assoc();

// Handle form submission for adding a new course
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_course'])) {
    $course_name = $_POST['course_name'];
    $teacher_id = $_POST['teacher_id'];

    $stmt = $conn->prepare("INSERT INTO courses (name, semester_id) VALUES (?, ?)");
    $stmt->bind_param("si", $course_name, $semester_id);
    
    if ($stmt->execute()) {
        $course_id = $stmt->insert_id;

        $stmt = $conn->prepare("INSERT INTO course_teachers (course_id, teacher_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $course_id, $teacher_id);
        
        if ($stmt->execute()) {
            echo "<p>Course added successfully!</p>";
        } else {
            echo "<p>Error adding teacher to course: " . $stmt->error . "</p>";
        }
    } else {
        echo "<p>Error adding course: " . $stmt->error . "</p>";
    }
}

// Fetch courses for this semester
$stmt = $conn->prepare("SELECT c.*, t.name as teacher_name 
                        FROM courses c 
                        LEFT JOIN course_teachers ct ON c.id = ct.course_id 
                        LEFT JOIN teachers t ON ct.teacher_id = t.id 
                        WHERE c.semester_id = ?");
$stmt->bind_param("i", $semester_id);
$stmt->execute();
$courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch all teachers for the dropdown
$teachers = $conn->query("SELECT id, name FROM teachers")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Courses - <?php echo htmlspecialchars($semester['semester']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2ecc71;
            --background-color: #ecf0f1;
            --text-color: #34495e;
            --transition-speed: 0.3s;
        }

        body {
            font-family: 'Roboto', sans-serif;
            line-height: 1.6;
            color: var(--text-color);
            background-color: var(--background-color);
            margin: 0;
            padding: 20px;
            transition: background-color var(--transition-speed);
        }

        h1, h2 {
            color: var(--primary-color);
            margin-bottom: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            transition: box-shadow var(--transition-speed);
        }

        form:hover {
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            transition: border-color var(--transition-speed);
        }

        input[type="text"]:focus, select:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color var(--transition-speed);
        }

        button:hover {
            background-color: #2980b9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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

        tr {
            transition: background-color var(--transition-speed);
        }

        tr:hover {
            background-color: #f5f5f5;
        }

        a {
            color: var(--primary-color);
            text-decoration: none;
            transition: color var(--transition-speed);
        }

        a:hover {
            color: #2980b9;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            margin-right: 5px;
        }

        .edit-btn {
            background-color: var(--secondary-color);
            color: white;
        }

        .delete-btn {
            background-color: #e74c3c;
            color: white;
        }

        .back-btn {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 4px;
            transition: background-color var(--transition-speed);
        }

        .back-btn:hover {
            background-color: #2980b9;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
    </style>
</head>
<body>
    <h1>Manage Courses for <?php echo htmlspecialchars($semester['semester']); ?></h1>

    <h2>Add New Course</h2>
    <form method="POST">
        <input type="text" name="course_name" placeholder="Course Name" required>
        <select name="teacher_id" required>
            <option value="">Select Teacher</option>
            <?php foreach ($teachers as $teacher): ?>
                <option value="<?php echo $teacher['id']; ?>"><?php echo htmlspecialchars($teacher['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="add_course">Add Course</button>
    </form>

    <h2>Courses</h2>
    <table>
        <thead>
            <tr>
                <th>Course Name</th>
                <th>Teacher</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($courses)): ?>
                <tr>
                    <td colspan="3">No courses found for this semester.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($courses as $course): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($course['name']); ?></td>
                        <td><?php echo htmlspecialchars($course['teacher_name']); ?></td>
                        <td>
                            <!-- Add edit and delete buttons here -->
                            <a href="edit_course.php?id=<?php echo $course['id']; ?>">Edit</a>
                            <a href="delete_course.php?id=<?php echo $course['id']; ?>" onclick="return confirm('Are you sure you want to delete this course?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="semester.php">Back to Semester List</a>
</body>
</html>