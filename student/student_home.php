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
    <title>Student Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
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
            background-color: var(--card-color);
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        .course-card h3 {
            margin-top: 0;
            color: var(--primary-color);
        }

        .completed-evaluations {
            color: #133915;
        }

        .completed-evaluations .check-mark {
            font-size: 36px;
            margin-bottom: 10px;
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

        .view-teachers-btn {
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

        .view-teachers-btn:hover {
            background-color: #160859;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            transform: translateY(-2px);
        }

        .view-teachers-btn:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        .btn-text {
            margin-right: 10px;
        }

        .btn-icon {
            font-size: 1.2em;
            transition: transform 0.3s ease;
        }

        .view-teachers-btn:hover .btn-icon {
            transform: translateX(5px);
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .fade-in {
            animation: fadeIn 0.5s ease-in;
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

    /* Remove this rule if it exists */
    /* .dropdown:hover .dropdown-content {
        display: block;
    } */

    #user-menu {
        cursor: pointer;
    }
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
        <h2>Semester Evaluations</h2>
        <div class="course-list">
            <?php 
            // Get the current student's ID
            $student_id = $_SESSION['user_id']; // Assuming you store the student's ID in the session

            // Fetch all semesters for the current year
            $current_year = date('Y');
            $query = "SELECT * FROM semester WHERE YEAR(start_date) = ? ORDER BY start_date ASC";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $current_year);
            $stmt->execute();
            $result = $stmt->get_result();
            $semesters = $result->fetch_all(MYSQLI_ASSOC);

            if (!empty($semesters)) {
                foreach ($semesters as $semester) {
                    // Get total number of teachers for this semester
                    $query = "SELECT COUNT(*) as total FROM semester_teachers WHERE semester_id = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $semester['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $total_teachers = $result->fetch_assoc()['total'];

                    // Get number of teachers already evaluated by this student for this semester
                    $query = "SELECT COUNT(DISTINCT st.teacher_id) as evaluated 
                              FROM semester_teachers st
                              LEFT JOIN evaluations e ON st.teacher_id = e.teacher_id AND e.student_id = ?
                              WHERE st.semester_id = ? AND e.id IS NOT NULL";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ii", $student_id, $semester['id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $evaluated_teachers = $result->fetch_assoc()['evaluated'];

                    // Calculate pending evaluations
                    $pending_evaluations = $total_teachers - $evaluated_teachers;

                    // Determine the year to display
                    $display_year = date('Y', strtotime($semester['start_date']));

                    // Start of the card div
                    echo "<div class='course-card' id='evaluation-status-card-{$semester['id']}'>";
                    echo "<h3>{$semester['semester']} {$display_year}</h3>";
                    
                    if ($pending_evaluations > 0) {
                        echo "<p>{$pending_evaluations} Pending Evaluations</p>";
                        echo "<p>{$evaluated_teachers} / {$total_teachers} Teachers Evaluated</p>";
                    } else {
                        echo '<div class="completed-evaluations">';
                        echo '<span class="check-mark">&#10004;</span>';
                        echo '<p>All Evaluations Completed</p>';
                        echo "<p>{$total_teachers} / {$total_teachers} Teachers Evaluated</p>";
                        echo '</div>';
                    }

                    // Add a link to list_of_teachers.php with the semester ID
                    echo "<a href='list_of_teachers.php?semester_key={$semester['id']}' class='view-teachers-btn' onclick='event.stopPropagation(); console.log(\"Clicked semester: {$semester['id']}\"); return true;'>
                        <span class='btn-text'>View Teachers</span>
                        <span class='btn-icon'>&#10095;</span>
                    </a>";

                    echo "</div>"; // End of the card div
                }
            } else {
                echo "<div class='course-card'>";
                echo "<h3>No Active Semesters</h3>";
                echo "<p>There are no active semesters for the current year.</p>";
                echo "</div>";
            }
            ?>
        </div>
    </section>
    <aside class="announcements fade-in">
        <h2>Announcements</h2>
        <ul id="announcement-list">
            <li>Campus event next week</li>
            <li>Library hours extended for evaluation</li>
            <li>New online resources available</li>
        </ul>
    </aside>
</main>
    <footer>
        <p>&copy; 2024 Student Evaluation System. All rights reserved.</p>
    </footer>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Your existing JavaScript code

            // Add fade-in effect to course cards
            const courseCards = document.querySelectorAll('.course-card');
            courseCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in');
                }, index * 100);
            });

            // Simulating dynamic announcements
            const announcementList = document.getElementById('announcement-list');
            setTimeout(() => {
                const newAnnouncement = document.createElement('li');
                newAnnouncement.textContent = 'New announcement: Online evaluation workshop this Friday!';
                newAnnouncement.style.color = var(--secondary-color);
                newAnnouncement.style.fontWeight = 'bold';
                newAnnouncement.classList.add('fade-in');
                announcementList.prepend(newAnnouncement);
            }, 3000);
        });
    </script>
    <script>
document.addEventListener('DOMContentLoaded', (event) => {
    const userMenu = document.getElementById('user-menu');
    const dropdownContent = document.getElementById('dropdown-content');

    userMenu.addEventListener('click', (e) => {
        e.stopPropagation();
        dropdownContent.style.display = dropdownContent.style.display === 'block' ? 'none' : 'block';
    });

    // Close the dropdown when clicking outside of it
    document.addEventListener('click', (e) => {
        if (!userMenu.contains(e.target) && !dropdownContent.contains(e.target)) {
            dropdownContent.style.display = 'none';
        }
    });

    // Your existing JavaScript code...
});
</script>
</body>
</html>