<html>
<head>
<title>Teacher Home Page</title>
<style>
body {
    font-family: 'Arial', sans-serif;
    margin: 0;
    padding: 0;
    background-color: #f0f5f9;
    color: #333;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}
header {
    background-color: #1e88e5;
    color: white;
    padding: 1rem;
    text-align: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
nav {
    background-color: #242a00;
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
    font-weight: bold;
    transition: color 0.3s ease;
}
nav ul li a:hover {
    color: #ffd54f;
}
main {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 1rem;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
    flex: 1;
}
.dashboard {
    background-color: white;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.announcements {
    background-color: #e3f2fd;
    border-radius: 8px;
    padding: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
h1, h2 {
    margin-top: 0;
}
.course-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}
.course-card {
    background-color: #bbdefb;
    border-radius: 8px;
    padding: 1rem;
    text-align: center;
    transition: background-color 0.3s ease;
    cursor: pointer;
}
.course-card:hover {
    transform: translateY(-5px);
}
footer {
    background-color: #390e71;
    color: white;
    text-align: center;
    padding: 1rem;
    margin-top: auto;
}
nav1 {
    background-color: #242a00;
    padding: 0.5rem;
    display: flex;
    justify-content: flex-end;
    align-items: center;
    position: relative;
}
nav1 h3 {
    margin: 0;
    color: white;
    cursor: pointer;
}
.dropdown {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background-color: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 4px;
    overflow: hidden;
    z-index: 1;
}
.dropdown a {
    display: block;
    padding: 0.5rem 1rem;
    color: #333;
    text-decoration: none;
}
.dropdown a:hover {
    background-color: #f0f0f0;
}
.completed-evaluations {
    color: #28a745;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}
.completed-evaluations .check-mark {
    font-size: 48px;
    margin-bottom: 10px;
}
.completed-evaluations h3 {
    margin: 0;
}
#evaluation-status-card.all-completed {
    background-color: #d4edda;
    border-color: #c3e6cb;
}
</style>
</head>
<body>
<?php
session_start();
$username = isset($_SESSION['username']) && !empty($_SESSION['username']) ? $_SESSION['username'] : 'Guest';
$teacher_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
include '../connection/conn.php';

if (!$teacher_id) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit();
}

// Fetch teacher's information
$stmt = $conn->prepare("SELECT name, subject FROM teachers WHERE id = ?");
$stmt->bind_param("i", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();
$stmt->close();

?>
<nav1>
    <h3 id="user-menu">Welcome, <?php echo htmlspecialchars($teacher['name']); ?></h3>
    <div class="dropdown" id="dropdown-menu">
        <a href="logout.php">Logout</a>
    </div>
</nav1>
    <header style="background: linear-gradient(to right, #3e0074, #7633ff);">
        <h1>Teacher Dashboard</h1>
    </header>
    <nav>
        <ul>
            <li><a href="teacher_home.php">Dashboard</a></li>
            <li><a href="teacher_evaluations.php">Evaluations</a></li>
            <li><a href="teacher_profile.php">Profile</a></li>
        </ul>
    </nav>
    <main>
        <section class="dashboard">
            <h2>Welcome back, <?php echo htmlspecialchars($teacher['name']); ?>!</h2>
            <div class="course-list">
                <div class="course-card" id="evaluation-status-card">
                <?php 
                    // // Get total number of questions
                    // $query = "SELECT COUNT(*) as total FROM custom_questions";
                    // $result = $conn->query($query);
                    // $row = $result->fetch_assoc();
                    // $total_questions = $row['total'];

                    // // Get number of questions already answered by this teacher
                    // $query = "SELECT COUNT(*) as answered FROM custom_questions WHERE id = ? AND answer IS NOT NULL";
                    // $stmt = $conn->prepare($query);
                    // $stmt->bind_param("i", $teacher_id);
                    // $stmt->execute();
                    // $result = $stmt->get_result();
                    // $row = $result->fetch_assoc();
                    // $answered_questions = $row['answered'];
                    // $stmt->close();

                    // // Calculate pending questions
                    // $pending_questions = $total_questions - $answered_questions;

                    // if ($pending_questions > 0) {
                    //     echo '<h3>' . $pending_questions . ' Pending Questions</h3>';
                    // } else {
                    //     echo '<div class="completed-evaluations">';
                    //     echo '<span class="check-mark">&#10004;</span>';
                    //     echo '<h3>All Questions Answered</h3>';
                    //     echo '</div>';
                    // }
                ?>
                <p>First Semester, 2024</p>
                </div>
                <div class="course-card disabled" style="opacity: 0.6; cursor: not-allowed;">
                    <h3>0 Pending Evaluations</h3>
                    <p>Second Semester, 2024</p>
                </div>
                <div class="course-card disabled" style="opacity: 0.6; cursor: not-allowed;">
                    <h3>0 Pending Evaluations</h3>
                    <p>Final Evaluation, 2024</p>
                </div>
                <div class="course-card disabled" style="opacity: 0.6; cursor: not-allowed;">
                    <h3>Literature 301</h3>
                    <p>Essay feedback available</p>
                </div>
            </div>
        </section>
        <aside class="announcements">
            <h2>Announcements</h2>
            <ul>
                <li>Campus event next week</li>
                <li>Library hours extended for evaluation</li>
                <li>New online resources available</li>
            </ul>
        </aside>
    </main>
    <footer>
        <p>Copyright &copy; 2024. All rights reserved.</p>
    </footer>
<script src="https://kit.fontawesome.com/your-fontawesome-kit.js"></script>
<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const courseCards = document.querySelectorAll('.course-card:not(.disabled)');
    courseCards.forEach(card => {
        // Add data attribute to each course card
        const courseName = card.querySelector('h3').textContent;
        card.setAttribute('data-course', courseName);

        card.addEventListener('click', () => {
            const courseName = card.getAttribute('data-course');
            window.location.href = `list_of_teachers.php?course=${encodeURIComponent(courseName)}`;
        });
    });

    // Simulating dynamic content
    const announcements = document.querySelector('.announcements ul');
    setTimeout(() => {
        const newAnnouncement = document.createElement('li');
        newAnnouncement.textContent = 'New announcement: Online evaluation this weekend!';
        newAnnouncement.style.color = '#d32f2f';
        newAnnouncement.style.fontWeight = 'bold';
        announcements.prepend(newAnnouncement);
    }, 3000);

    const userMenu = document.getElementById('user-menu');
    const dropdownMenu = document.getElementById('dropdown-menu');

    userMenu.addEventListener('click', () => {
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', (event) => {
        if (!userMenu.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = 'none';
        }
    });

    // Check if all evaluations are completed and change the card color
    document.addEventListener('DOMContentLoaded', function() {
        var card = document.getElementById('evaluation-status-card');
        var completedDiv = card.querySelector('.completed-evaluations');
        if (completedDiv) {
            card.classList.add('all-completed');
        }
    });
});
</script>
</body></html>