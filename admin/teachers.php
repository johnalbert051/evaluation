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

// Fetch teachers data
$sql = "SELECT id, name, subject, username FROM teachers";
$result = $conn->query($sql);

$teachers = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $teachers[] = $row;
    }
}

$conn->close();
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
            margin-bottom: 30px;
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

        .btn-edit {
            color: #2ecc71;
            cursor: pointer;
        }

        .btn-delete {
            color: #e74c3c;
            cursor: pointer;
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

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .modal-header {
            padding: 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .modal-close {
            font-size: 1.5rem;
            color: #aaa;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.2s;
        }

        .modal-close:hover {
            color: #333;
        }

        .modal-body {
            padding: 20px;
        }

        .form-grid {
            display: grid;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }

        .form-group input,
        .form-group select {
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            font-size: 1rem;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
        }

        .modal-footer {
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
        }

        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
        }
        .btn-primary {
            background-color: var(--secondary-color);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--primary-color);
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }
        .action-buttons .btn-danger {
            color: #fff;
            background-color: red !important;
        }
        .btn-danger:hover {
            background-color: #e80000 !important;
        }

        @media (max-width: 600px) {
            .modal-content {
                width: 95%;
            }
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
                <input type="text" placeholder="Search...">
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

        <h2>Teacher List</h2>
        <button class="add-teacher-button" id="openModal">Add New Teacher</button>
        <table>
            <thead>
            <tr>
                <th>Name</th>
                <th>Subject</th>
                <th>Username</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody id="teachersTableBody">
                <!-- Table rows will be inserted here by JavaScript -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="teacherModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Add New Teacher</h2>
            <button class="modal-close" id="closeModal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-grid">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter teacher's name">
                </div>
                <div class="form-group">
                    <label for="subject">Subject</label>
                    <input type="text" id="subject" name="subject" required placeholder="Enter subject">
                </div>
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required placeholder="Enter username">
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter password">
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required placeholder="Confirm password">
                </div>
                <input type="hidden" id="role" name="role" value="teacher">
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" id="cancelModal">Cancel</button>
            <button class="btn btn-primary" id="saveTeacher">Add Teacher</button>
        </div>
    </div>
</div>
<?php
// Include database connection
include "../connection/conn.php";

// Check if form data is received
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $subject = $_POST['subject'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $role = $_POST['role'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO teachers (name, subject, username, password,role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $subject, $username, $password,$role);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New teacher added successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request";
}
?>
<script>
    // Get modal element
    var modal = document.getElementById("teacherModal");
    // Get open modal button
    var openModal = document.getElementById("openModal");
    // Get close button
    var closeBtn = document.getElementById("closeModal");

    // Listen for open click
    openModal.addEventListener("click", function() {
        modal.style.display = "flex";
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

    // Handle form submission
    document.getElementById("saveTeacher").addEventListener("click", function(event) {
        event.preventDefault(); // Prevent any default button action

        // Get all input fields
        var name = document.getElementById("name").value.trim();
        var subject = document.getElementById("subject").value.trim();
        var username = document.getElementById("username").value.trim();
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;

        // Check if any field is empty
        if (!name || !subject || !username || !password || !confirmPassword) {
            alert("Please fill in all fields.");
            return;
        }

        // Check if passwords match
        if (password !== confirmPassword) {
            alert("Passwords do not match!");
            return;
        }

        // If all validations pass, proceed with form submission
        var formData = new FormData();
        formData.append('name', name);
        formData.append('subject', subject);
        formData.append('username', username);
        formData.append('password', password);
        formData.append('role', document.getElementById("role").value);

        fetch('teachers.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.includes("New teacher added successfully")) {
                alert("Teacher successfully added");
                modal.style.display = "none";
                location.reload(); // Reload the page to see the new teacher
            } else {
                alert(data);
            }
        })
        .catch(error => console.error('Error:', error));
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

    let teachers = []

    function loadTeachers() {
        fetch('get_teachers.php')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    teachers = data.teachers;
                    renderTeachers(teachers);
                } else {
                    console.error('Error loading teachers:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    function createTeacherRow(teacher) {
        return `
            <tr>
                <td>${teacher.name}</td>
                <td>${teacher.subject}</td>
                <td>${teacher.username}</td>
                <td class="action-buttons">
                    <button class="btn btn-primary" onclick="viewTeacher(${teacher.id})">
                        <i class="fas fa-eye"></i> View
                    </button>
                    <button class="btn btn-danger" onclick="deleteTeacher(${teacher.id})">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                </td>
            </tr>
        `;
    }

    function renderTeachers(teachersToRender) {
        const teacherGrid = document.getElementById('teachersTableBody');
        teacherGrid.innerHTML = teachersToRender.map(createTeacherRow).join('');
    }

    function searchTeachers() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const filteredTeachers = teachers.filter(teacher => 
            teacher.name.toLowerCase().includes(searchTerm) ||
            teacher.subject.toLowerCase().includes(searchTerm) ||
            teacher.username.toLowerCase().includes(searchTerm)
        );
        renderTeachers(filteredTeachers);
    }

    // Load teachers when the page loads
    loadTeachers();

    // Setup search functionality
    document.getElementById('searchInput').addEventListener('input', searchTeachers);

    // ... (implement viewTeacher, deleteTeacher, and other necessary functions)

    function deleteTeacher(teacherId) {
        if (confirm('Are you sure you want to delete this teacher?')) {
            fetch('delete_teacher.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${encodeURIComponent(teacherId)}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Teacher deleted successfully');
                    loadTeachers(); // Reload the teachers list
                } else {
                    alert('Error deleting teacher: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the teacher');
            });
        }
    }
</script>
</body>
</html>