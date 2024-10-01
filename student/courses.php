<?php
session_start();
include '../connection/conn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: ../index.php");
    exit();
}

$username = isset($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$student_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses - Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
          :root {
            --primary-color: #433878;
            --secondary-color: #f50057;
            --background-color: #f5f5f5;
            --card-color: #ffffff;
            --text-color: #333333;
            --nav-color: #160859;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--background-color);
            color: var(--text-color);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav1 {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        nav1 h3 {
            margin: 0;
            font-weight: 500;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
            border-radius: 4px;
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

        #user-menu {
            cursor: pointer;
        }

        nav {
            background-color: var(--nav-color);
            padding: 0.5rem;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        nav ul li {
            margin: 0 1rem;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            padding: 0.5rem 1rem;
            border-radius: 4px;
        }

        nav ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

       main {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 2rem;
            flex: 1;
        }

        .dashboard {
            background-color: var(--card-color);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        h2 {
            margin-top: 0;
            color: var(--primary-color);
            font-weight: 500;
        }

        .course-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .course-card {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .course-title {
            font-size: 20px;
            font-weight: bold;
            color: #160859;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .course-info {
            margin-bottom: 15px;
        }

        .course-info p {
            margin: 8px 0;
            color: #555;
        }

        .course-actions {
            display: flex;
            justify-content: flex-start;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-transform: uppercase;
            transition: background-color 0.3s ease;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background-color: #45a049;
        }

        .btn-secondary {
            background-color: #f0f0f0;
            color: #333;
        }

        .btn-secondary:hover {
            background-color: #e0e0e0;
        }

        .status-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 8px;
        }

        .status-completed {
            background-color: #4CAF50;
        }

        .status-pending {
            background-color: #FFC107;
        }

        .active-status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.8em;
            font-weight: bold;
        }

        .active-status.active {
            background-color: #4CAF50;
            color: white;
        }

        .active-status.inactive {
            background-color: #f44336;
            color: white;
        }

        .announcements {
            background-color: var(--card-color);
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .announcements ul {
            padding-left: 20px;
        }

        .announcements li {
            margin-bottom: 10px;
        }

        footer {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 1rem;
            margin-top: auto;
        }

        .search-bar {
            margin-bottom: 20px;
        }
        #searchInput {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        .course-actions {
            margin-top: 15px;
        }

        .view-evaluations-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background-color: #433878;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .view-evaluations-btn:hover {
            background-color: #160859;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }

        .view-evaluations-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .view-evaluations-btn:hover .btn-icon {
            transform: translateX(5px);
        }

        .btn-text {
            margin-right: 10px;
        }

        .btn-icon {
            font-size: 1.2em;
            transition: transform 0.3s ease;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: #000;
            text-decoration: none;
            cursor: pointer;
        }

        .modal-actions {
            margin-top: 20px;
            text-align: right;
        }

        .course-details {
            padding: 15px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: bold;
            color: #555;
        }

        .detail-value {
            color: #333;
        }

        .status-indicator {
            display: inline-block;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-right: 5px;
        }

        .status-indicator.active {
            background-color: #4CAF50;
        }

        .status-indicator.inactive {
            background-color: #f44336;
        }

        .error-message {
            color: #f44336;
            text-align: center;
            padding: 10px;
        }
        .course-details-wrapper {
    opacity: 0;
    transform: translateY(-20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}

.course-details-wrapper.show {
    opacity: 1;
    transform: translateY(0);
}

.course-details {
    padding: 15px;
    background-color: #f9f9f9;
    border-radius: 5px;
}

/* ... rest of your existing CSS ... */
    </style>
</head>
<body>
    <nav1>
        <h3>Student Dashboard</h3>
        <div class="dropdown">
            <h3 id="user-menu"><?php echo htmlspecialchars($username); ?> ▼</h3>
            <div id="dropdown-content" class="dropdown-content">
                <a href="profile.php">Profile</a>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </nav1>
    <nav>
        <ul>
            <li><a href="student_home.php">Dashboard</a></li>
            <li><a href="courses.php">Courses</a></li>
            <li><a href="#">Resources</a></li>
            <li><a href="profile.php">Profile</a></li>
        </ul>
    </nav>
    <main>
        <section class="dashboard fade-in">
            <h2>Your Courses</h2>
            <div class="search-bar">
                <input type="text" id="searchInput" placeholder="Search courses...">
            </div>
            <div class="course-list" id="courseGrid">
                <!-- Course cards will be dynamically inserted here -->
            </div>
        </section>
        <aside class="announcements fade-in">
            <h2>Course Announcements</h2>
            <ul id="announcement-list">
                <li>New course materials available</li>
                <li>Upcoming assignment deadline</li>
                <li>Virtual study group session this week</li>
            </ul>
        </aside>
    </main>
    <footer>
        <p>&copy; 2024 Student Evaluation System. All rights reserved.</p>
    </footer>
    <div id="courseModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle"></h2>
            <div id="modalBody"></div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const userMenu = document.getElementById('user-menu');
            const dropdownContent = document.getElementById('dropdown-content');

            userMenu.addEventListener('click', (e) => {
                e.stopPropagation();
                dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', (e) => {
                if (!userMenu.contains(e.target) && !dropdownContent.contains(e.target)) {
                    dropdownContent.style.display = 'none';
                }
            });

            function fetchCourses() {
                fetch('get_courses.php')
                    .then(response => response.json())
                    .then(data => {
                        renderCourses(data);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function createCourseCard(course) {
                const activeStatus = course.status  ? 'active' : 'inactive';
                const activeText = course.status ? 'Active' : 'Inactive';
                
                return `
                    <div class="course-card fade-in">
                        <div class="course-title">
                            <span class="status-indicator status-${course.status}"></span>
                            ${course.course_name}
                        </div>
                        <div class="course-info">
                            <p><strong>Code:</strong> ${course.course_code}</p>
                            <p><strong>Instructor:</strong> ${course.instructor}</p>
                            <p><strong>Semester:</strong> ${course.semester}</p>
                            <p><strong>Status:</strong> <span class="active-status ${activeStatus}">${activeText}</span></p>
                        </div>
                        <div class="course-actions">
                            <a href="#" class="view-evaluations-btn" onclick="openModal(${course.id}); event.preventDefault();">
                                <span class="btn-text">View Details</span>
                                <span class="btn-icon">&#10095;</span>
                            </a>
                        </div>
                    </div>
                `;
            }

            function renderCourses(courses) {
                const courseGrid = document.getElementById('courseGrid');
                courseGrid.innerHTML = courses.map(createCourseCard).join('');
            }

            function searchCourses() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                fetch(`get_courses.php?search=${encodeURIComponent(searchTerm)}`)
                    .then(response => response.json())
                    .then(data => {
                        renderCourses(data);
                    })
                    .catch(error => console.error('Error:', error));
            }

            function viewEvaluations(courseId) {
                console.log(`Viewing evaluations for course ID: ${courseId}`);
                window.location.href = `view_evaluations.php?id=${courseId}`;
            }

            function editCourse(courseId) {
                console.log(`Editing course ID: ${courseId}`);
                window.location.href = `edit_course.php?course_id=${courseId}`;
            }

            // Initial fetch and render
            fetchCourses();

            // Setup search functionality
            document.getElementById('searchInput').addEventListener('input', searchCourses);

            // Simulating dynamic announcements
            const announcementList = document.getElementById('announcement-list');
            setTimeout(() => {
                const newAnnouncement = document.createElement('li');
                newAnnouncement.textContent = 'New announcement: Course feedback survey now open!';
                newAnnouncement.style.color = '#f50057';
                newAnnouncement.style.fontWeight = 'bold';
                newAnnouncement.classList.add('fade-in');
                announcementList.prepend(newAnnouncement);
            }, 3000);
        });

        function openModal(courseId) {
    const modal = document.getElementById('courseModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalBody = document.getElementById('modalBody');

    fetch(`get_course_details.php?id=${courseId}`)
        .then(response => response.json())
        .then(course => {
            if (course.error) {
                modalBody.innerHTML = `<p class="error-message">${course.error}</p>`;
            } else {
                modalTitle.textContent = course.course_name;
                modalBody.innerHTML = `
                    <div class="course-details-wrapper">
                        <div class="course-details">
                            <div class="detail-row">
                                <span class="detail-label">Course Code:</span>
                                <span class="detail-value">${course.course_code}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Instructor:</span>
                                <span class="detail-value">${course.instructor}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Semester:</span>
                                <span class="detail-value">${course.semester}</span>
                            </div>
                            <div class="detail-row">
                                <span class="detail-label">Status:</span>
                                <span class="detail-value">
                                    <span class="status-indicator ${course.status ? 'active' : 'inactive'}"></span>
                                    ${course.status ? 'Active' : 'Inactive'}
                                </span>
                            </div>
                        </div>
                    </div>
                `;
            }
            modal.style.display = 'block';
            // Trigger the animation after a short delay
            setTimeout(() => {
                document.querySelector('.course-details-wrapper').classList.add('show');
            }, 50);
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = `<p class="error-message">Error fetching course details</p>`;
            modal.style.display = 'block';
        });
}

        document.querySelector('.close').onclick = function() {
            document.getElementById('courseModal').style.display = 'none';
        }
    </script>
</body>
</html>