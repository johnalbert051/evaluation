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

// Fetch semesters from the database
$semesterQuery = "SELECT id, semester, date_created FROM semester ORDER BY date_created DESC, semester DESC";
$semesterResult = mysqli_query($conn, $semesterQuery);
$semesters = mysqli_fetch_all($semesterResult, MYSQLI_ASSOC);

// Specific page logic goes here
// For example, fetching teachers for teachers.php or students for students.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses Table</title>
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
        }

        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: white;
            padding: 20px;
            height: 100vh;
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
            width: 200px; /* Adjust this value as needed */
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
            border-radius: 10px;
            overflow: hidden;
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
            background-color: #f5f5f5;
        }

        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-active {
            background-color: #2ecc71;
        }

        .status-inactive {
            background-color: #e74c3c;
        }

        .action-buttons button {
            background-color: var(--secondary-color);
            margin-right: 5px;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--secondary-color);
        }
        .action-buttons .btn-danger {
            background-color: red !important;
        }
        .btn-danger:hover {
            background-color: #e80000 !important;
        }
        .btn-secondary {
            background-color: #95a5a6;
        }

        .btn-primary:hover {
            background-color:var(--primary-color)!important;
        }

        .btn-secondary:hover {
            background-color: #7f8c8d !important;
        }

        .add-course-btn {
            display: block;
            width: 10%;
            padding: 10px;
            background-color: #2ecc71;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
            transition: background-color 0.3s ease;
        }

        .add-course-btn:hover {
            background-color: #27ae60;
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
            background-color: rgba(0,0,0,0.5);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #ffffff;
            width: 90%;
            max-width: 500px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
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
            background-color: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        @media (max-width: 600px) {
            .modal-content {
                width: 95%;
            }
        }

        .add-course-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--secondary-color);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-bottom: 20px;
        }

        .add-course-button:hover {
            background-color: var(--primary-color);
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
                    <input type="text" id="searchInput" placeholder="Search courses...">
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
            <h2>Courses List</h2>
            <button class="add-course-button" id="openModal">Add New Course</button>
            <table>
                <thead>
                    <tr>
                        <th>Course Name</th>
                        <th>Course Code</th>
                        <th>Instructor</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="courseGrid">
                    <!-- Course rows will be dynamically inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Adding a New Course -->
    <div id="addCourseModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add New Course</h2>
                <button class="modal-close" id="closeModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="courseName">Course Name</label>
                        <input type="text" id="courseName" placeholder="Enter course name">
                    </div>
                    <div class="form-group">
                        <label for="courseCode">Course Code</label>
                        <input type="text" id="courseCode" placeholder="Enter course code">
                    </div>
                    <div class="form-group">
                        <label for="instructor">Instructor</label>
                        <input type="text" id="instructor" placeholder="Enter instructor name">
                    </div>
                    <div class="form-group">
                        <label for="semester">Semester</label>
                        <select id="semester" name="semester">
                            <option value="">Select Semester</option>
                            <?php
                            $semesterQuery = "SELECT DISTINCT semester FROM semester ORDER BY semester";
                            $semesterResult = $conn->query($semesterQuery);
                            while ($semesterRow = $semesterResult->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($semesterRow['semester']) . "'>" . htmlspecialchars($semesterRow['semester']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" id="cancelModal">Cancel</button>
                <button class="btn btn-primary" id="saveCourse">Save</button>
            </div>
        </div>
    </div>

    <script>
        // Modal functionality
        const modal = document.getElementById('addCourseModal');
        const openModalBtn = document.getElementById('openModal');
        const closeModalBtn = document.getElementById('closeModal');
        const cancelModalBtn = document.getElementById('cancelModal');
        const saveCourseBtn = document.getElementById('saveCourse');

        openModalBtn.addEventListener('click', () => {
            resetModalForm();
            modal.style.display = 'flex';
        });

        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        cancelModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        // Remove the sample course data
        let courses = [];

        // Function to load courses from the database
        function loadCourses() {
            fetch('get_courses.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        courses = data.courses;
                        renderCourses(courses);
                    } else {
                        console.error('Error loading courses:', data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }

        function createCourseRow(course) {
            return `
                <tr>
                    <td>${course.course_name}</td>
                    <td>${course.course_code}</td>
                    <td>${course.instructor}</td>
                    <td>${course.semester}</td>
                    <td><span class="status-indicator status-${course.status}"></span>${course.status.charAt(0).toUpperCase() + course.status.slice(1)}</td>
                    <td class="action-buttons">
                        <button class="btn btn-primary" onclick="viewEvaluations(${course.id})">
                            <i class="fas fa-eye"></i> View
                        </button>
                        <button class="btn btn-danger" onclick="deleteCourse(${course.id})">
                            <i class="fas fa-trash-alt"></i> Delete
                        </button>
                    </td>
                </tr>
            `;
        }

        function renderCourses(coursesToRender) {
            const courseGrid = document.getElementById('courseGrid');
            courseGrid.innerHTML = coursesToRender.map(createCourseRow).join('');
        }

        function searchCourses() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const filteredCourses = courses.filter(course => 
                course.course_name.toLowerCase().includes(searchTerm) ||
                course.course_code.toLowerCase().includes(searchTerm) ||
                course.instructor.toLowerCase().includes(searchTerm)
            );
            renderCourses(filteredCourses);
        }

        function viewEvaluations(courseId) {
            fetch(`get_course.php?id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);
                    if (data.success) {
                        const course = data.course;
                        document.getElementById('courseName').value = course.course_name;
                        document.getElementById('courseCode').value = course.course_code;
                        document.getElementById('instructor').value = course.instructor;
                        
                        // Set the semester dropdown
                        const semesterSelect = document.getElementById('semester');
                        if (semesterSelect) {
                            for (let i = 0; i < semesterSelect.options.length; i++) {
                                if (semesterSelect.options[i].value == course.semester) {
                                    semesterSelect.selectedIndex = i;
                                    break;
                                }
                            }
                        }
                        
                        document.getElementById('status').value = course.status;
                        
                        // Change modal title and button text
                        document.querySelector('.modal-header h2').textContent = 'Edit Course';
                        document.getElementById('saveCourse').textContent = 'Update';
                        
                        // Set a data attribute to know we're editing
                        document.getElementById('saveCourse').setAttribute('data-course-id', courseId);
                        
                        // Show the modal
                        modal.style.display = 'flex';
                    } else {
                        console.error('Error fetching course data:', data);
                        alert('Error fetching course data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching the course data');
                });
        }

        function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course?')) {
                fetch('delete_course.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${encodeURIComponent(courseId)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Course deleted successfully');
                        loadCourses(); // Reload the courses list
                    } else {
                        alert('Error deleting course: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the course');
                });
            }
        }

        // Load courses when the page loads
        loadCourses();

        // Setup search functionality
        document.getElementById('searchInput').addEventListener('input', searchCourses);

        // Modify the saveCourseBtn event listener
        saveCourseBtn.addEventListener('click', () => {
            const name = document.getElementById('courseName').value;
            const code = document.getElementById('courseCode').value;
            const instructor = document.getElementById('instructor').value;
            const semesterId = document.getElementById('semester').value;
            const status = document.getElementById('status').value;
            const courseId = document.getElementById('saveCourse').getAttribute('data-course-id');

            if (name && code && instructor && semesterId) {
                const url = courseId ? 'update_course.php' : 'add_course.php';
                let formData = new FormData();
                formData.append('name', name);
                formData.append('code', code);
                formData.append('instructor', instructor);
                formData.append('semester', semesterId);
                formData.append('status', status);

                if (courseId) {
                    formData.append('id', courseId);
                }

                fetch(url, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadCourses();
                        modal.style.display = 'none';
                        resetModalForm();
                        alert(courseId ? 'Course updated successfully!' : 'Course added successfully!');
                    } else {
                        throw new Error(data.message || 'Unknown error');
                    }
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    alert('An error occurred: ' + error.message);
                });
            } else {
                alert('Please fill in all fields.');
            }
        });

        function resetModalForm() {
            document.getElementById('courseName').value = '';
            document.getElementById('courseCode').value = '';
            document.getElementById('instructor').value = '';
            document.getElementById('semester').value = '';
            document.getElementById('status').value = 'active';
            document.querySelector('.modal-header h2').textContent = 'Add New Course';
            document.getElementById('saveCourse').textContent = 'Save';
            document.getElementById('saveCourse').removeAttribute('data-course-id');
        }

        function resetForm() {
            document.getElementById('courseName').value = ''
            document.getElementById('courseCode').value = ''
            document.getElementById('instructor').value = ''
            document.getElementById('semester').value = ''
            document.getElementById('status').value = 'active'
            
            // Reset modal title and button text
            document.querySelector('.modal-header h2').textContent = 'Add New Course'
            document.getElementById('saveCourse').textContent = 'Save'
            
            // Remove the course ID data attribute
            document.getElementById('saveCourse').removeAttribute('data-course-id')
        }

        function editCourse(courseId) {
            fetch(`get_course.php?id=${courseId}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Server response:', data);
                    if (data.success) {
                        const course = data.course;
                        document.getElementById('courseName').value = course.course_name;
                        document.getElementById('courseCode').value = course.course_code;
                        document.getElementById('instructor').value = course.instructor;
                        
                        // Set the semester dropdown
                        const semesterSelect = document.getElementById('semester');
                        if (semesterSelect) {
                            semesterSelect.value = course.semester; // This should now match the semester name
                        }
                        
                        document.getElementById('status').value = course.status;
                        
                        // Change modal title and button text
                        document.querySelector('.modal-header h2').textContent = 'Edit Course';
                        document.getElementById('saveCourse').textContent = 'Update';
                        
                        // Set a data attribute to know we're editing
                        document.getElementById('saveCourse').setAttribute('data-course-id', courseId);
                        
                        // Show the modal
                        modal.style.display = 'flex';
                    } else {
                        console.error('Error fetching course data:', data);
                        alert('Error fetching course data: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while fetching the course data');
                });
        }

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
    </script>
</body>
</html>