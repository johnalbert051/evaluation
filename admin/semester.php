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
    <title>Semester Management</title>
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
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            /* height: 100vh; */
            overflow-y: auto;
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
            width: 200px;
        }

        .search-bar i {
            color: var(--text-color);
            margin-right: 5px;
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
            align-items: center; /* This will vertically align the text and icon */
        }
        .dropbtn i {
            margin-left: 5px; /* This adds some space between the text and the icon */
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            right: 0;
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
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .action-buttons a, .action-buttons span {
            margin-right: 5px;
            cursor: pointer;
        }
        .btn-manage, .btn-questions {
            padding: 5px 10px;
            border-radius: 3px;
            text-decoration: none;
            color: white;
        }
        .btn-manage { background-color: #3498db; }
        .btn-questions { background-color: #2ecc71; }
        .btn-edit { color: #f39c12; }
        .btn-delete { color: #e74c3c; }

        .add-teacher-button {
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

        .action-buttons .btn-danger {
            color: #fff;
            background-color: red !important;
        }
        .btn-danger:hover {
            background-color: #e80000 !important;
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.2s;
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
                <a href="settings.php" class="nav-link">
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
                <input type="text" id="searchInput" placeholder="Search semesters...">
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

        <h2>Semester List</h2>
        <button class="add-teacher-button" id="openModal">Add New Semester</button>
        <table>
            <thead>
            <tr>
                <th>Semester Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Courses</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php
            // Database connection
            include "../connection/conn.php";

            // Fetch semesters from the database
            $sql = "SELECT * FROM semester";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["semester"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["start_date"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["end_date"]) . "</td>";
                    echo "<td class='course-buttons'>
                            <a href='evaluation_list.php?semester_id=" . $row["id"] . "&semester_name=" . urlencode($row["semester"]) . "' class='btn-manage'><i class='fas fa-tasks'></i> Manage Teachers</a>
                            <a href='custom_question.php?semester_id=" . $row["id"] . "' class='btn-questions'><i class='fas fa-question-circle'></i> Manage Questions</a>
                          </td>";
                    echo "<td class='action-buttons'>
                            <button class='btn btn-danger' onclick='deleteSemester(" . $row["id"] . ")'>
                                <i class='fas fa-trash-alt'></i> Delete
                            </button>
                          </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No semesters found</td></tr>";
            }

            $conn->close();
            ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div id="teacherModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Add New Semester</h2>
        <form method="POST" action="">
            <div class="form-group">
                <label for="semester_name">Semester Name:</label>
                <select id="semester_name" name="semester_name" required>
                    <option value="">Select a semester</option>
                    <option value="First Semester">First Semester</option>
                    <option value="Second Semester">Second Semester</option>
                    <option value="Summer Semester">Summer Semester</option>
                </select>
            </div>
            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>
            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>
            <div class="form-group">
                <button type="submit" name="submit">Add Semester</button>
            </div>
        </form>
    </div>
</div>

<?php
// Add this PHP code to handle form submission
if (isset($_POST['submit'])) {
    // Include database connection
    include "../connection/conn.php";

    // Get form data
    $semester_name = $_POST['semester_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $date_created = date('Y-m-d H:i:s'); // Current date and time

    // Prepare SQL statement
    $sql = "INSERT INTO semester (semester, start_date, end_date, date_created, status) VALUES (?, ?, ?, ?, ?)";
    
    // Create a prepared statement
    $stmt = $conn->prepare($sql);
    
    // Set the status
    $status = 'active';
    
    // Bind parameters
    $stmt->bind_param("sssss", $semester_name, $start_date, $end_date, $date_created, $status);
    
    // Execute the statement
    if ($stmt->execute()) {
        echo "<script>alert('New semester added successfully'); window.location.href='semester.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); console.log('" . $stmt->error . "');</script>";
    }

    // Close statement and connection
    $stmt->close();
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

    // Add event listeners for edit and delete buttons
    document.querySelectorAll('.btn-edit').forEach(btn => {
        btn.addEventListener('click', function() {
            const semesterId = this.getAttribute('data-id');
            // Implement edit functionality
            console.log('Edit semester with ID:', semesterId);
        });
    });

    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', function() {
            const semesterId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this semester?')) {
                // Implement delete functionality
                console.log('Delete semester with ID:', semesterId);
            }
        });
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

    function deleteSemester(id) {
        if (confirm('Are you sure you want to delete this semester? This action cannot be undone.')) {
            fetch('delete_semester.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'id=' + id
            })
            .then(response => response.text())  // Change this line
            .then(text => {
                console.log('Raw response:', text);  // Log the raw response
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + text);
                }
            })
            .then(data => {
                if (data.success) {
                    alert('Semester deleted successfully');
                    location.reload();
                } else {
                    throw new Error('Server error: ' + (data.message || 'Unknown error'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while deleting the semester: ' + error.message);
            });
        }
    }
</script>
</body>
</html>