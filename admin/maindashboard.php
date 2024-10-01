<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}


// Check if the user is logged in and the name is set in the session
$admin_name = isset($_SESSION['admin_name']) ? htmlspecialchars($_SESSION['admin_name']) : 'Admin';
?>
<html><head>
    <title>Teacher Evaluation System</title>
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

.show{
    display: block;
}
.dropdown-content a i {
    margin-right: 10px;
}

.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.card {
    background-color: white;
    border-radius: 10px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.card-title {
    font-size: 18px;
    font-weight: 500;
}

.card-icon {
    font-size: 24px;
    color: var(--primary-color);
}

.evaluation-progress {
    height: 8px;
    background-color: #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
    margin-top: 10px;
}

.progress-bar {
    height: 100%;
    background-color: var(--primary-color);
    width: 75%;
    transition: width 2s ease-in-out;
}

.teacher-list {
    list-style-type: none;
    padding: 0;
}

.teacher-item {
    display: flex;
    align-items: center;
    margin-bottom: 15px;
}

.teacher-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.teacher-info {
    flex: 1;
}

.teacher-name {
    font-weight: 500;
}

.teacher-subject {
    font-size: 12px;
    color: #777;
}

.evaluation-score {
    font-weight: 600;
    color: var(--primary-color);
}

.chart-container {
    height: 200px;
}

.count-list {
    list-style-type: none;
    padding: 0;
}

.count-item {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.count-item i {
    font-size: 20px;
    margin-right: 10px;
    color: var(--primary-color);
}

.count-label {
    font-weight: 500;
    margin-right: 5px;
}

.count-value {
    font-weight: 600;
    color: var(--accent-color);
}

.count-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100px;
}

.count-value {
    font-size: 3em;
    font-weight: bold;
    color: var(--primary-color);
}

.count-label {
    font-size: 1em;
    color: var(--text-color);
    margin-top: 5px;
}

.card-icon {
    font-size: 1.5em;
    color: var(--accent-color);
}
.scrollable-list {
    max-height: 300px;
    overflow-y: auto;
    padding-right: 10px;
}

.scrollable-list::-webkit-scrollbar {
    width: 8px;
}

.scrollable-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.scrollable-list::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

.scrollable-list::-webkit-scrollbar-thumb:hover {
    background: #555;
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
                <a href="#" class="nav-link">
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
                    <i class="fas fa-clipboard-check"></i>
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
        <!-- (and other admin pages) -->
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
        <h2>Dashboard List</h2>
        <div class="dashboard-grid">
            <?php
            // Define the entities
            include "../connection/conn.php";
            $entities = [
                ['name' => 'Students', 'table' => 'student_table', 'icon' => 'fas fa-user-graduate'],
                ['name' => 'Teachers', 'table' => 'teachers', 'icon' => 'fas fa-chalkboard-teacher'],
                ['name' => 'Courses', 'table' => 'course', 'icon' => 'fas fa-book']
            ];

            // Create a card for each entity
            foreach ($entities as $entity) {
                $result = $conn->query("SELECT COUNT(*) as count FROM {$entity['table']}");
                $count = $result ? $result->fetch_assoc()['count'] : 'N/A';
                ?>
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><?php echo $entity['name']; ?></h2>
                        <i class="<?php echo $entity['icon']; ?> card-icon"></i>
                    </div>
                    <div class="count-container">
                        <span class="count-value" data-target="<?php echo $count; ?>"><?php echo $count; ?></span>
                        <span class="count-label">Total <?php echo $entity['name']; ?></span>
                    </div>
                </div>
                <?php
            }

            // ... existing code ...

        // Calculate the evaluation progress
        $total_teachers_query = "SELECT COUNT(*) as total FROM teachers";
        $total_students_query = "SELECT COUNT(*) as total FROM student_table";
        $completed_evaluations_query = "SELECT COUNT(*) as completed FROM evaluations";

        $total_teachers_result = $conn->query($total_teachers_query);
        $total_students_result = $conn->query($total_students_query);
        $completed_evaluations_result = $conn->query($completed_evaluations_query);

        $total_teachers = $total_teachers_result->fetch_assoc()['total'];
        $total_students = $total_students_result->fetch_assoc()['total'];
        $completed_evaluations = $completed_evaluations_result->fetch_assoc()['completed'];

        $total_possible_evaluations = $total_teachers * $total_students;
        $progress_percentage = ($total_possible_evaluations > 0) ? round(($completed_evaluations / $total_possible_evaluations) * 100) : 0;

?>
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Overall Evaluation Progress</h2>
        <i class="fas fa-chart-line card-icon"></i>
    </div>
    <p id="evaluation-progress-text">0% of evaluations completed</p>
    <div class="evaluation-progress">
        <div id="progress-bar" class="progress-bar" style="width: 0%"></div>
    </div>
</div>

<style>
    .progress-bar {
        transition: width 2s ease-in-out;
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function animateProgressBar() {
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('evaluation-progress-text');
        const targetPercentage = <?php echo $progress_percentage; ?>;
        let currentPercentage = 0;

        const interval = setInterval(() => {
            if (currentPercentage >= targetPercentage) {
                clearInterval(interval);
                progressText.textContent = targetPercentage + '% of evaluations completed';
            } else {
                currentPercentage++;
                progressBar.style.width = currentPercentage + '%';
                progressText.textContent = currentPercentage + '% of evaluations completed';
            }
        }, 20); // Adjust this value to make the animation faster or slower
    }

    // Call the function when the page loads
    animateProgressBar();
});
</script>

            <!-- Existing cards -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Top Performing Teachers</h2>
                    <i class="fas fa-trophy card-icon"></i>
                </div>
                <div class="scrollable-list">
                    <ul class="teacher-list">
                        <?php
                        // Make sure you have your database connection established here
                        include '../connection/conn.php';

                        // Query to select top-performing teachers
                        $top_teachers_query = "
                            SELECT t.id, t.name AS teacher_name, t.subject, t.rating
                            FROM teachers t
                            WHERE t.rating IS NOT NULL
                            ORDER BY t.rating DESC
                            LIMIT 10
                        ";
                        $top_teachers_result = $conn->query($top_teachers_query);

                        if ($top_teachers_result && $top_teachers_result->num_rows > 0) {
                            $rank = 1;
                            while ($row = $top_teachers_result->fetch_assoc()) {
                                ?>
                                <li class="teacher-item">
                                    <img src="https://i.pravatar.cc/100?img=<?php echo $rank; ?>" alt="Teacher <?php echo $rank; ?>" class="teacher-avatar">
                                    <div class="teacher-info">
                                        <div class="teacher-name">
                                            <?php 
                                            echo htmlspecialchars($row['teacher_name']);
                                            if ($rank <= 3) {
                                                $medal = $rank == 1 ? '🥇' : ($rank == 2 ? '🥈' : '🥉');
                                                echo ' ' . $medal;
                                            }
                                            ?>
                                        </div>
                                        <div class="teacher-subject"><?php echo htmlspecialchars($row['subject']); ?></div>
                                    </div>
                                    <div class="evaluation-score">
                                        <?php 
                                        if (isset($row['rating']) && is_numeric($row['rating'])) {
                                            echo number_format($row['rating'], 1);
                                        } else {
                                            echo 'N/A';
                                        }
                                        ?>
                                    </div>
                                </li>
                                <?php
                                $rank++;
                            }
                        } else {
                            echo "<li>No top-performing teachers found.</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Evaluation Categories</h2>
                    <i class="fas fa-list-ul card-icon"></i>
                </div>
                <div class="chart-container" id="categoryChart"></div>
            </div>
            <div class="card">
    <div class="card-header">
        <h2 class="card-title">Recent Evaluations</h2>
        <i class="fas fa-clipboard-list card-icon"></i>
    </div>
    <div class="scrollable-list">
        <ul class="teacher-list">
            <?php
            // Query to select recent evaluations with teacher ratings
            $recent_evaluations_query = "
                SELECT e.id, e.evaluation_date, t.name AS teacher_name, t.subject, t.rating
                FROM evaluations e
                JOIN teachers t ON e.teacher_id = t.id
                ORDER BY e.id DESC
                LIMIT 10
            ";
            $recent_evaluations_result = $conn->query($recent_evaluations_query);

            if ($recent_evaluations_result && $recent_evaluations_result->num_rows > 0) {
                while ($row = $recent_evaluations_result->fetch_assoc()) {
                    ?>
                    <li class="teacher-item">
                        <div class="teacher-info">
                            <div class="teacher-name"><?php echo htmlspecialchars($row['teacher_name']); ?></div>
                            <div class="teacher-subject"><?php echo htmlspecialchars($row['subject']); ?></div>
                        </div>
                        <div class="evaluation-score">
                            <?php 
                            if (isset($row['rating']) && is_numeric($row['rating'])) {
                                echo number_format($row['rating'], 1);
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </div>
                    </li>
                    <?php
                }
            } else {
                echo "<li>No recent evaluations found.</li>";
            }
            ?>
        </ul>
    </div>
</div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM fully loaded');
    const dropdownBtn = document.querySelector('.dropbtn');
    const dropdownContent = document.querySelector('.dropdown-content');

    console.log('Dropdown button:', dropdownBtn);
    console.log('Dropdown content:', dropdownContent);

    if (dropdownBtn && dropdownContent) {
        dropdownBtn.addEventListener('click', function(e) {
            console.log('Dropdown button clicked');
            e.stopPropagation();
            dropdownContent.classList.toggle('show');
            console.log('Dropdown visibility:', dropdownContent.classList.contains('show'));
        });

        document.addEventListener('click', function(e) {
            if (!dropdownBtn.contains(e.target) && !dropdownContent.contains(e.target)) {
                console.log('Clicked outside dropdown');
                dropdownContent.classList.remove('show');
            }
        });
    } else {
        console.error('Dropdown elements not found');
    }

    const ctx = document.getElementById('categoryChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Teaching Methods', 'Student Engagement', 'Content Knowledge', 'Classroom Management', 'Professional Development'],
            datasets: [{
                data: [30, 25, 20, 15, 10],
                backgroundColor: [
                    '#3498db',
                    '#2ecc71',
                    '#e74c3c',
                    '#f39c12',
                    '#9b59b6'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right'
            }
        }
    });

    function updateEvaluationProgress() {
        fetch('get_evaluation_progress.php')
            .then(response => response.json())
            .then(data => {
                const progressBar = document.querySelector('.progress-bar');
                const progressText = document.getElementById('evaluation-progress-text');
                progressBar.style.width = `${data.percentage}%`;
                progressText.textContent = `${data.percentage}% of evaluations completed`;
            });
    }

    // Update evaluation progress every 30 seconds
    setInterval(updateEvaluationProgress, 30000);

    // Simulating real-time updates
    setInterval(() => {
        // Update top performing teachers
        const teacherList = document.querySelectorAll('.teacher-list')[0];
        const teachers = Array.from(teacherList.children);
        teachers.forEach(teacher => {
            const score = teacher.querySelector('.evaluation-score');
            const newScore = (Math.random() * 0.5 + 9.3).toFixed(1);
            score.textContent = newScore;
        });
        teachers.sort((a, b) => {
            return parseFloat(b.querySelector('.evaluation-score').textContent) - 
                   parseFloat(a.querySelector('.evaluation-score').textContent);
        });
        teacherList.innerHTML = '';
        teachers.forEach(teacher => teacherList.appendChild(teacher));

        // Update recent evaluations
        const recentEvaluations = document.querySelectorAll('.teacher-list')[1];
        const recentTeachers = Array.from(recentEvaluations.children);
        recentTeachers.forEach(teacher => {
            const score = teacher.querySelector('.evaluation-score');
            const newScore = (Math.random() * 1.5 + 7.5).toFixed(1);
            score.textContent = newScore;
        });
    }, 5000);

    function animateCount(element) {
        const target = parseInt(element.getAttribute('data-target'), 10);
        console.log('Target value:', target); // Debugging line
        if (isNaN(target)) {
            console.error('Invalid target value for element:', element);
            return;
        }
        let current = 0;
        const duration = 2000; // 2 seconds
        const step = target / (duration / 16); // 60 fps

        function updateCount() {
            current += step;
            if (current >= target) {
                element.textContent = target;
            } else {
                element.textContent = Math.round(current);
                requestAnimationFrame(updateCount);
            }
        }

        updateCount();
    }

    // Start the animation for count values
    const countElements = document.querySelectorAll('.count-value');
    console.log('Found count elements:', countElements.length); // Debugging line
    countElements.forEach(animateCount);

    // Remove or comment out the existing setInterval that updates these values
    // setInterval(() => {
    //     // Update top performing teachers
    //     ...
    // }, 5000);
});
</script>
</body>
</html>