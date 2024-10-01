<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Include your database connection
include "../connection/conn.php";

// Check if the user is logged in and the name is set in the session
$admin_name = isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin';

// Specific page logic goes here
// For example, fetching teachers for teachers.php or students for students.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher List</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap');
        @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');

        :root {
            --primary-color: #160859;
            --secondary-color: #433878;
            --background-color: #ecf0f1;
            --text-color: #34495e;
            --accent-color: #e74c3c;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: var(--text-color);
            height: 100vh;
            overflow: hidden;
        }

        .container {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            height: 100%;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar-divider {
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0), rgba(255, 255, 255, 0.75), rgba(255, 255, 255, 0));
            margin: 15px 0;
        }

        .nav-menu {
            list-style-type: none;
            padding: 0;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            color: white;
            text-decoration: none;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .nav-link:hover {
            background-color: var(--secondary-color);
        }

        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: white;
            border-radius: 20px;
            padding: 5px 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            border: none;
            outline: none;
            padding: 5px;
            font-size: 14px;
        }

        .search-bar i {
            color: var(--text-color);
        }

        .user-profile {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        .user-profile img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .dropdown {
            position: relative;
        }

        .dropbtn {
            background-color: transparent;
            color: var(--text-color);
            padding: 10px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .dropbtn i {
            margin-left: 5px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown-content a i {
            margin-right: 10px;
        }

        .show {
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: var(--primary-color);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
        }

        .btn-danger {
            background-color: var(--accent-color);
            color: white;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .btn i {
            margin-right: 5px;
        }

        .add-teacher-button {
            width: 200px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 20px;
            display: inline-block;
            text-decoration: none;
        }

        .add-teacher-button:hover {
            background-color: #2980b9;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .form-group button {
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .form-group button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="sidebar">
        <div class="logo">Teachers Evaluation</div>
        <hr class="sidebar-divider">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="maindashboard.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="teachers.php" class="nav-link">
                    <i class="fas fa-user-tie"></i>
                    <span>Teachers</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="student.php" class="nav-link">
                    <i class="fas fa-user-graduate"></i>
                    <span>Students</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="courses.php" class="nav-link">
                    <i class="fas fa-book"></i>
                    <span>Courses</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a href="semester.php" class="nav-link">
                    <i class="fas fa-calendar-alt"></i>
                    <span>Semester</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="evaluation_top_ratings.php" class="nav-link">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Evaluations</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="report.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </li>
        </ul>
    </div>
    <div class="main-content">
        <div class="header">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" id="searchInput" placeholder="Search students...">
            </div>
            <div class="user-profile">
                <img src="https://i.pravatar.cc/100?img=12" alt="Admin profile picture">
                <div class="dropdown">
                    <span class="dropbtn"><?php echo $admin_name; ?> <i class="fas fa-caret-down"></i></span>
                    <div class="dropdown-content">
                        <a href="#"><i class="fas fa-user"></i> Profile</a>
                        <a href="#"><i class="fas fa-cog"></i> Settings</a>
                        <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </div>
                </div>
            </div>
        </div>

        <h2>Student List</h2>
        <button class="add-teacher-button" id="openModal">Add New Student</button>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Major</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="studentTableBody">
                <!-- Student rows will be inserted here by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="teacherModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Student</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="subject">Major:</label>
                <input type="text" id="subject" name="subject" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm-password">Confirm Password:</label>
                <input type="password" id="confirm-password" name="confirm-password" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <input type="text" id="role" name="role" value="student" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Add Student</button>
            </div>
        </form>
    </div>
</div>

<?php
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $major = $_POST['subject'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $role = $_POST['role'];

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit;
    }

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Database connection
    include "../connection/conn.php";

    // Insert to student_table
    $sql1 = "INSERT INTO student_table (name, major, email, username, password, role) VALUES ('$name', '$major', '$email', '$username', '$hashed_password', '$role')";
    if ($conn->query($sql1) === TRUE) {
        echo "New Student added to student_table successfully";
    } else {
        echo "Error: " . $sql1 . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<script>
    // Get modal element
    var modal = document.getElementById("teacherModal");
    // Get open modal button
    var openModal = document.getElementById("openModal");
    // Get close button
    var closeBtn = document.getElementsByClassName("close")[0];

    // Listen for open click
    openModal.addEventListener("click", function() {
        modal.style.display = "block";
    });

    // Listen for close click
    closeBtn.addEventListener("click", function() {
        modal.style.display = "none";
    });

    // Listen for outside click
    window.addEventListener("click", function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const dropdownBtn = document.querySelector('.dropbtn');
        const dropdownContent = document.querySelector('.dropdown-content');

        dropdownBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownContent.classList.toggle('show');
        });

        document.addEventListener('click', function(e) {
            if (!dropdownBtn.contains(e.target) && !dropdownContent.contains(e.target)) {
                dropdownContent.classList.remove('show');
            }
        });
    });

    let students = []

    function loadStudents() {
        fetch('get_students.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    students = data.students;
                    renderStudents(students);
                } else {
                    console.error('Error loading students:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function createStudentRow(student) {
        return `
            <tr>
                <td>${student.name}</td>
                <td>${student.major}</td>
                <td>${student.email}</td>
                <td class="action-buttons">
                    <button class="btn btn-primary" onclick="editStudent(${student.id})">
                        <i class="fas fa-edit"></i> View
                    </button>
                    <button class="btn btn-danger" onclick="deleteStudent(${student.id})">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </td>
            </tr>
        `;
    }

    function renderStudents(studentsToRender) {
        const studentGrid = document.getElementById('studentTableBody');
        studentGrid.innerHTML = studentsToRender.map(createStudentRow).join('');
    }

    function deleteStudent(studentId) {
        if (confirm('Are you sure you want to delete this student?')) {
            fetch('delete_student.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${encodeURIComponent(studentId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Student deleted successfully');
                    loadStudents(); // Reload the students list
                } else {
                    alert('Error deleting student: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the student');
            });
        }
    }

    // Load students when the page loads
    loadStudents();

    // ... (other existing JavaScript code) ...
</script>
</body>
</html>